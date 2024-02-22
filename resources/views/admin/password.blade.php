<x-auth :title="$title">
	<div class="row mb-none-30">
		<div class="col-lg-3 col-md-3 mb-30">

			<div class="card b-radius--5 overflow-hidden">
				<div class="card-body p-0">
					<div class="d-flex bg--primary p-3">
						<div class="avatar avatar--lg">
							<img src="{{ asset('assets/user-icon-50.png') }}" alt="@lang('Image')">
						</div>
						<div class="ps-3">
							<h4 class="text--white">{{ __($admin->role) }}</h4>
						</div>
					</div>
					<ul class="list-group">
						<li class="list-group-item d-flex justify-content-between align-items-center">
							@lang('Role')
							<span class="fw-bold">{{ __($admin->role) }}</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							@lang('Username')
							<span class="fw-bold">{{ __($admin->username) }}</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							@lang('Email')
							<span class="fw-bold">{{ $admin->email }}</span>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="col-lg-9 col-md-9 mb-30">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title border-bottom mb-4 pb-2">@lang('Change Password')</h5>

					<form action="{{ route('admin.password.update') }}" method="POST" enctype="multipart/form-data">
						@csrf

						<div class="form-group">
							<label>@lang('Password')</label>
							<input class="form-control" name="old_password" type="password" required>
						</div>

						<div class="form-group">
							<label>@lang('New Password')</label>
							<input class="form-control" name="password" type="password" required>
						</div>

						<div class="form-group">
							<label>@lang('Confirm Password')</label>
							<input class="form-control" name="password_confirmation" type="password" required>
						</div>
						<button class="btn btn--primary w-100 btn-lg h-45" type="submit">@lang('Submit')</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-auth>
