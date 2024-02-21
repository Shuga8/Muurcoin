<x-no-auth :title="$title">
	<div class="login-main" style="background-image: url('{{ asset('admin/images/login.jpg') }}')">
		<div class="custom-container container">
			<div class="row justify-content-center">
				<div class="col-xxl-5 col-xl-5 col-lg-6 col-md-8 col-sm-11">
					<div class="login-area">
						<div class="login-wrapper">
							<div class="login-wrapper__top">
								<h3 class="title text-white">@lang('Welcome to') <strong>{{ __(config('app.name')) }}</strong></h3>
								<p class="text-white">{{ __($title) }} @lang('to') {{ __(config('app.name')) }}
									@lang('Dashboard')</p>
							</div>
							<div class="login-wrapper__body">
								<form class="cmn-form mt-30 verify-gcaptcha login-form" action="{{ route('admin.login.auth') }}" method="POST">
									@csrf
									<div class="form-group">
										<label>@lang('Username')</label>
										<input class="form-control" name="username" type="text" value="{{ old('username') }}" required>
									</div>
									<div class="form-group">
										<label>@lang('Password')</label>
										<input class="form-control" name="password" type="password" required>
									</div>
									<div class="d-flex justify-content-between flex-wrap">
										<div class="form-check me-3">
											<input class="form-check-input" id="remember" name="remember" type="checkbox">
											<label class="form-check-label" for="remember">@lang('Remember Me')</label>
										</div>
									</div>
									<button class="btn cmn-btn w-100" type="submit">@lang('LOGIN')</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-no-auth>
