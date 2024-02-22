<x-auth :title="$title">
	<div class="container">

		<div class="row text-dark rounded bg-white p-3">
			<form class="form" method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data">
				@csrf

				<div class="form-group">
					<label class="sm-only" for="username">Username</label>
					<input class="form-control" name="username" type="text">

					@error('username')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>

				<div class="form-group">
					<label class="sm-only" for="email">Email</label>
					<input class="form-control" name="email" type="email">

					@error('email')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>

				<div class="form-group">
					<label class="sm-only" for="role">Role</label>
					<select class="form-control" name="role">
						<option class="form-control" value="super-admin">Super Admin</option>
					</select>
				</div>

				<div class="form-group">
					<label for="sm-only">Password</label>
					<input class="form-control" name="password" type="password">
					@error('password')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>

				<div class="form-group">
					<label for="sm-only">Re-enter Password</label>
					<input class="form-control" name="password_confirmation" type="password">
					@error('password_confirmation')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>

				<div class="form-group">
					<button class="btn btn-primary p-3 text-white" type="submit">Submit</button>
				</div>
			</form>
		</div>

	</div>
</x-auth>
