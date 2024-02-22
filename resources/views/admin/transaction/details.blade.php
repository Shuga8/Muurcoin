<x-auth :title="$title">
	<div class="col-lg-12">
		<div class="card b-radius--10">
			<div class="card-body p-0">
				<div class="table-responsive--md table-responsive">
					<table class="table--light style--two table">
						<thead>
							<tr>
								<th>@lang('Ref id')</th>
								<th>@lang('Amount')</th>
								<th>@lang('Post Balance')</th>
								<th>@lang('Status')</th>
								<th>@lang('Created at')</th>
							</tr>
						</thead>
						<tbody>
							@unless ($transactions->count() == 0)
								@foreach ($transactions as $transaction)
									<tr>
										<td>{{ Str::limit($transaction->reference_id, $limit = 12) }}</td>
										<td>{{ $transaction->amount }}</td>
										<td>{{ $transaction->post_balance }}</td>
										<td>{{ $transaction->status }}</td>
										<td>
											{{ showDateTime($transaction->created_at) }} <br> {{ diffForHumans($transaction->created_at) }}
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

			@if ($transactions->hasPages())
				<div class="card-footer py-4">
					{{ paginateLinks($transactions) }}
				</div>
			@endif
		</div>


	</div>
</x-auth>
