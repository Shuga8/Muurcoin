<x-auth :title="$title">
	<div class="row gy-3">

		<div class="col-xxl-3 col-sm-6">
			<x-widget value="{{ $widget['total_users'] }}" title="Total Users" link="{{ route('admin.users.all') }}"
				icon="las la-users f-size--56" bg="primary" />
		</div>

		<div class="col-xxl-3 col-sm-6">
			<x-widget value="{{ $widget['verified_users'] }}" title="Active Users" link="{{ route('admin.users.active') }}"
				icon="las la-user-check f-size--56" bg="success" />
		</div>

		<div class="col-xxl-3 col-sm-6">
			<x-widget value="{{ $widget['email_unverified_users'] }}" title="Email Unverified Users"
				link="{{ route('admin.users.email.unverified') }}" icon="lar la-envelope f-size--56" bg="danger" />
		</div>

		<div class="col-xxl-3 col-sm-6">
			<x-widget value="{{ $widget['email_verified_users'] }}" title="Email Verified Users"
				link="{{ route('admin.users.all') }}" icon="lar la-envelope f-size--56" bg="primary" />
		</div>

		<div class="col-xxl-3 col-lg-12 p-3">
			<h3>Transaction Reports For The Last 30 Days</h3>
			<div class="text-primary w-full bg-white" id="transaction-chart"></div>
		</div>
	</div>

	@push('script')
		<script src="{{ asset('admin/js/vendor/apexcharts.min.js') }}"></script>
		<script src="{{ asset('admin/js/vendor/chart.js.2.8.0.js') }}"></script>
		<script>
			"use strict";
			// Transaction data from PHP to JavaScript
			var options = {
				chart: {
					height: 450,
					type: "area",
					toolbar: {
						show: false
					},
					dropShadow: {
						enabled: true,
						enabledSeries: [0],
						top: -2,
						left: 0,
						blur: 10,
						opacity: 0.08
					},
					animations: {
						enabled: true,
						easing: 'linear',
						dynamicAnimation: {
							speed: 1000
						}
					},
				},
				dataLabels: {
					enabled: false
				},
				series: [{
						name: "Plus Transactions",
						data: [
							@foreach ($trxReport['date'] as $trxDate)
								{{ @$plusTrx->where('date', $trxDate)->first()->amount ?? 0 }},
							@endforeach
						]
					},
					{
						name: "Minus Transactions",
						data: [
							@foreach ($trxReport['date'] as $trxDate)
								{{ @$minusTrx->where('date', $trxDate)->first()->amount ?? 0 }},
							@endforeach
						]
					}
				],
				fill: {
					type: "gradient",
					gradient: {
						shadeIntensity: 1,
						opacityFrom: 0.7,
						opacityTo: 0.9,
						stops: [0, 90, 100]
					}
				},
				xaxis: {
					categories: [
						@foreach ($trxReport['date'] as $trxDate)
							"{{ $trxDate }}",
						@endforeach
					]
				},
				grid: {
					padding: {
						left: 5,
						right: 5
					},
					xaxis: {
						lines: {
							show: false
						}
					},
					yaxis: {
						lines: {
							show: false
						}
					},
				},
			};

			var chart = new ApexCharts(document.querySelector("#transaction-chart"), options);

			chart.render();
		</script>
	@endpush

</x-auth>
