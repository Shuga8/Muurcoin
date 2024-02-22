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
							@unless ($deposits->count() == 0)
								@foreach ($deposits as $deposit)
									<tr>
										<td>{{ Str::limit($deposit->reference_id, $limit = 12) }}</td>
										<td>{{ $deposit->amount }}</td>
										<td>{{ $deposit->post_balance }}</td>
										<td>{{ $deposit->status }}</td>
										<td>
											{{ showDateTime($deposit->created_at) }} <br> {{ diffForHumans($deposit->created_at) }}
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

			@if ($deposits->hasPages())
				<div class="card-footer py-4">
					{{ paginateLinks($deposits) }}
				</div>
			@endif
		</div>


	</div>
</x-auth>
