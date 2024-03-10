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
								<th>@lang('Wallet')</th>
								<th>@lang('Account number')</th>
								<th>@lang('Account name')</th>
								<th>@lang('Bank name')</th>
								<th>@lang('Status')</th>
								<th>@lang('Created at')</th>
								<th>@lang('Action')</th>
							</tr>
						</thead>
						<tbody>
							@unless ($withdrawals->count() == 0)
								@foreach ($withdrawals as $withdrawal)
									<tr>
										<td>{{ $user->username }}</td>
										<td>{{ $withdrawal->amount }}</td>
										<td>{{ $withdrawal->wallet }}</td>
										<td>{{ $withdrawal->acc_number }}</td>
										<td>{{ $withdrawal->acc_name }}</td>
										<td>{{ $withdrawal->bank_name }}</td>
										<td>{{ $withdrawal->status }}</td>
										<td>
											{{ showDateTime($withdrawal->created_at) }} <br> {{ diffForHumans($withdrawal->created_at) }}
										</td>
										<td>
											<form action="{{ route('admin.withdraw.update', $withdrawal->id) }}" method="POST">
												@csrf
												@method('PUT')
												<input name="status" type="hidden" value="completed">
												<button class="btn-success text-whiten p-2">Accept</button>
											</form>

											<br>

											<form action="{{ route('admin.withdraw.update', $withdrawal->id) }}" method="POST">
												@csrf
												@method('PUT')
												<input name="status" type="hidden" value="failed">
												<button class="btn-danger text-whiten p-2">Decline</button>
											</form>
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

			@if ($withdrawals->hasPages())
				<div class="card-footer py-4">
					{{ paginateLinks($withdrawals) }}
				</div>
			@endif
		</div>


	</div>
</x-auth>
