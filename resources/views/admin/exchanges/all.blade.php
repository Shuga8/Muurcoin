<x-auth :title="$title">
	<div class="col-lg-12">
		<div class="card b-radius--10">
			<div class="card-body p-0">
				<div class="table-responsive--md table-responsive">
					<table class="table--light style--two table">
						<thead>
							<tr>
								<th>@lang('User')</th>
								<th>@lang('Amount')</th>
								<th>@lang('Charge')</th>
								<th>@lang('From')</th>
								<th>@lang('To')</th>
								<th>@lang('Created at')</th>
							</tr>
						</thead>
						<tbody>
							@unless ($exchanges->count() == 0)
								@foreach ($exchanges as $exchange)
									<tr>
										<td>{{ $exchange->user->username }}</td>
										<td>{{ $exchange->amount }}</td>
										<td>{{ $exchange->charge }}</td>
										<td>{{ $exchange->from_wallet == 'MUURCOIN' ? 'MRCN' : $exchange->from_wallet }}</td>
										<td>{{ $exchange->to_wallet == 'MUURCOIN' ? 'MRCN' : $exchange->to_wallet }}</td>
										<td>
											{{ showDateTime($exchange->created_at) }} <br> {{ diffForHumans($exchange->created_at) }}
										</td>
									</tr>
								@endforeach
							@else
								<td class="p-1" colspan="5">{{ __($emptyMessage) }}</td>
							@endunless
						</tbody>
					</table>
				</div>
			</div>

			@if ($exchanges->hasPages())
				<div class="card-footer py-4">
					{{ paginateLinks($exchanges) }}
				</div>
			@endif
		</div>


	</div>
</x-auth>
