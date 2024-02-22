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
							@unless ($withdrawals->count() == 0)
								@foreach ($withdrawals as $withdrawal)
									<tr>
										<td>{{ Str::limit($withdrawal->reference_id, $limit = 12) }}</td>
										<td>{{ $withdrawal->amount }}</td>
										<td>{{ $withdrawal->post_balance }}</td>
										<td>{{ $withdrawal->status }}</td>
										<td>
											{{ showDateTime($withdrawal->created_at) }} <br> {{ diffForHumans($withdrawal->created_at) }}
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
