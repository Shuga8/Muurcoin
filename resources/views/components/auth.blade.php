<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ $title }}</title>

	<link type="image/x-icon" href="{{ asset('favicon.ico') }}" rel="shortcut icon">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link href="{{ asset('global/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('admin/css/vendor/bootstrap-toggle.min.css') }}" rel="stylesheet">
	<link href="{{ asset('global/css/all.min.css') }}" rel="stylesheet">
	<link href="{{ asset('global/css/line-awesome.min.css') }}" rel="stylesheet">

	@stack('style-lib')

	<link href="{{ asset('admin/css/vendor/select2.min.css') }}" rel="stylesheet">
	<link href="{{ asset('admin/css/app.css') }}" rel="stylesheet">


	@stack('style')
</head>

<body>

	<div class="page-wrapper default-version">
		@include('admin.partials.sidenav')
		@include('admin.partials.topnav')

		<div class="body-wrapper">
			<div class="bodywrapper__inner">

				@include('admin.partials.breadcrumb')

				{{ $slot }}


			</div><!-- bodywrapper__inner end -->
		</div><!-- body-wrapper end -->
	</div>




	<script src="{{ asset('global/js/jquery-3.6.0.min.js') }}"></script>
	<script src="{{ asset('global/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('admin/js/vendor/bootstrap-toggle.min.js') }}"></script>
	<script src="{{ asset('admin/js/vendor/jquery.slimscroll.min.js') }}"></script>


	@include('partials.notify')
	@stack('script-lib')

	<script src="{{ asset('admin/js/nicEdit.js') }}"></script>

	<script src="{{ asset('admin/js/vendor/select2.min.js') }}"></script>
	<script src="{{ asset('admin/js/app.js') }}"></script>

	{{-- LOAD NIC EDIT --}}
	<script>
		"use strict";
		bkLib.onDomLoaded(function() {
			$(".nicEdit").each(function(index) {
				$(this).attr("id", "nicEditor" + index);
				new nicEditor({
					fullPanel: true
				}).panelInstance('nicEditor' + index, {
					hasPanel: true
				});
			});
		});
		(function($) {
			$(document).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain', function() {
				$('.nicEdit-main').focus();
			});
		})(jQuery);
	</script>

	@stack('script')


</body>

</html>
