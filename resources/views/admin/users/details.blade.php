<x-auth :title="$title">

	<div class="row">
		<div class="col-12">
			<div class="row gy-4">
				<div class="col-xxl-3 col-sm-6">
					<div class="widget-two style--two box--shadow2 b-radius--5 bg--19">
						<div class="widget-two__icon b-radius--5 bg--primary">
							<i class="las la-money-bill-wave-alt"></i>
						</div>
						<div class="widget-two__content">
							<h3 class="text-white">
								@php
									$u_balance = json_decode($user->balance, true);
								@endphp
								<span class="fw-bold" style="color: white">
									MRCN: {{ @$u_balance['MUURCOIN'] }}
								</span> <br />
								<span class="fw-bold" style="color: white">
									USDT: {{ @$u_balance['USDT'] }}
								</span> <br />
								<span class="fw-bold" style="color: white">
									BTC: {{ @$u_balance['BTC'] }}
								</span>
							</h3>
							<p class="text-white">@lang('Balance')</p>
						</div>
						<a class="widget-two__btn"
							href="{{ route('admin.report.transaction') }}?search={{ $user->username }}">@lang('View All')</a>
					</div>
				</div>
				<div class="col-xxl-3 col-sm-6">
					<div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
						<div class="widget-two__icon b-radius--5 bg--primary">
							<i class="las la-wallet"></i>
						</div>
						<div class="widget-two__content">
							<h3 class="text-white">{{ $widget['total_deposit'] }}</h3>
							<p class="text-white">@lang('Deposits')</p>
						</div>
						<a class="widget-two__btn"
							href="{{ route('admin.deposit.list') }}?search={{ $user->username }}">@lang('View All')</a>
					</div>
				</div>
				<div class="col-xxl-3 col-sm-6">
					<div class="widget-two style--two box--shadow2 b-radius--5 bg--1">
						<div class="widget-two__icon b-radius--5 bg--primary">
							<i class="fas fa-wallet"></i>
						</div>
						<div class="widget-two__content">
							<h3 class="text-white">{{ $widget['total_withdrawals'] }}
							</h3>
							<p class="text-white">@lang('Withdrawals')</p>
						</div>
						<a class="widget-two__btn"
							href="{{ route('admin.withdraw.log') }}?search={{ $user->username }}">@lang('View All')</a>
					</div>
				</div>
				<div class="col-xxl-3 col-sm-6">
					<div class="widget-two style--two box--shadow2 b-radius--5 bg--17">
						<div class="widget-two__icon b-radius--5 bg--primary">
							<i class="las la-exchange-alt"></i>
						</div>
						<div class="widget-two__content">
							<h3 class="text-white">{{ $widget['total_transaction'] }}</h3>
							<p class="text-white">@lang('Transactions')</p>
						</div>
						<a class="widget-two__btn"
							href="{{ route('admin.report.transaction') }}?search={{ $user->username }}">@lang('View All')</a>
					</div>
				</div>
			</div>
			<div class="d-flex mt-4 flex-wrap gap-3">
				<div class="flex-fill">
					<button class="btn btn--success btn--shadow w-100 btn-lg bal-btn" data-bs-toggle="modal"
						data-bs-target="#addSubModal" data-act="add">
						<i class="las la-plus-circle"></i> @lang('Balance')
					</button>
				</div>

				<div class="flex-fill">
					<button class="btn btn--danger btn--shadow w-100 btn-lg bal-btn" data-bs-toggle="modal"
						data-bs-target="#addSubModal" data-act="sub">
						<i class="las la-minus-circle"></i> @lang('Balance')
					</button>
				</div>

				<div class="flex-fill">
					@if ($user->status == 'active')
						<button class="btn btn--warning btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal"
							data-bs-target="#userStatusModal" type="button">
							<i class="las la-ban"></i>@lang('Ban User')
						</button>
					@else
						<button class="btn btn--success btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal"
							data-bs-target="#userStatusModal" type="button">
							<i class="las la-undo"></i>@lang('Unban User')
						</button>
					@endif
				</div>
			</div>


			<div class="card mt-30">
				<div class="card-header">
					<h5 class="card-title mb-0">@lang('Information of') {{ $user->fullname }}</h5>
				</div>
				<div class="card-body">
					<form action="{{ route('admin.users.update', [$user->id]) }}" method="POST" enctype="multipart/form-data">
						@csrf

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>@lang('First Name')</label>
									<input class="form-control" name="firstname" type="text" value="{{ $user->firstname }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label">@lang('Last Name')</label>
									<input class="form-control" name="lastname" type="text" value="{{ $user->lastname }}" required>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>@lang('Email') </label>
									<input class="form-control" name="email" type="email" value="{{ $user->email }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label>@lang('Mobile Number') </label>
									<div class="input-group">
										<span class="input-group-text mobile-code">{{ $user->country_code }}</span>
										<input class="form-control checkUser" id="mobile" name="mobile" type="number"
											value="{{ $user->mobile }}" required>
									</div>
								</div>
							</div>
						</div>


						{{-- <div class="row">
							<div class="form-group col-xl-3 col-md-6 col-12">
								<label>@lang('Email Verification')</label>
								<input name="ev" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
									data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" type="checkbox"
									@if ($user->email_verified_at) checked @endif>

							</div>

							<div class="form-group col-xl-3 col-md-6 col-12">
								<label>@lang('Mobile Verification')</label>
								<input name="sv" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
									data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" type="checkbox"
									@if ($user->sv) checked @endif>

							</div>
							<div class="form-group col-xl-3 col-md- col-12">
								<label>@lang('2FA Verification') </label>
								<input name="ts" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger"
									data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" type="checkbox"
									@if ($user->ts) checked @endif>
							</div>
							<div class="form-group col-xl-3 col-md- col-12">
								<label>@lang('KYC') </label>
								<input name="kv" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger"
									data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" type="checkbox"
									@if ($user->kv == 1) checked @endif>
							</div>
						</div> --}}


						<div class="row mt-4">
							<div class="col-md-12">
								<div class="form-group">
									<button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')
									</button>
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>

		</div>
	</div>



	{{-- Add Sub Balance MODAL --}}
	<div class="modal fade" id="addSubModal" role="dialog" tabindex="-1">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><span class="type"></span> <span>@lang('Balance')</span></h5>
					<button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
						<i class="las la-times"></i>
					</button>
				</div>
				<form action="{{ route('admin.users.add.sub.balance', $user->id) }}" method="POST">
					@csrf
					<input name="act" type="hidden">
					<div class="modal-body">
						<div class="form-group">
							<label>@lang('Amount')</label>
							<div class="input-group">
								<input class="form-control" name="amount" type="number" step="any" placeholder="@lang('Please provide positive amount')"
									required>
							</div>
						</div>
						<div class="form-group">
							<label>@lang('Wallet')</label>
							<select class="form-control" name="wallet" required>
								<option selected disabled>@lang('Select One')</option>
							</select>
						</div>
						<div class="form-group">
							<label>@lang('Remark')</label>
							<textarea class="form-control" name="remark" placeholder="@lang('Remark')" rows="4" required></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</x-auth>
