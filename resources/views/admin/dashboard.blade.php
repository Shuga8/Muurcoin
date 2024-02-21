<x-auth :title="$title">

	<div class="row gx-5">
		<div class="col-xxl-3 col-sm-6">
			<x-widget value="{{ $widget['total_users'] }}" title="Total Users" link="{{ route('admin.users.all') }}"
				icon="las la-users f-size--56" bg="primary" />
		</div>
	</div>

</x-auth>
