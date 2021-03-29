<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>@yield('title')</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="{{ asset('multilevel/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
		<link rel="stylesheet" href="{{ asset('multilevel/css/animate.min.css') }}">
		<link rel="stylesheet" href="{{ asset('multilevel/css/fontawesome-all.css') }}">
		<link rel="stylesheet" href="{{ asset('multilevel/css/style.css') }}">
		<link href="{{ asset('assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="clearfix"></div>
		<div class="wrapper">
			@yield('content')
		</div>
		<script src="{{ asset('multilevel/js/jquery-3.3.1.min.js') }}"></script>
		<script src="{{ asset('multilevel/js/jquery.validate.min.js') }}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
		<script src="{{ asset('multilevel/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('multilevel/js/main.js') }}"></script>
		<script src="{{ asset('multilevel/js/switch.js') }}"></script>
		<script>
			$("#files").change(function() {
				filename = this.files[0].name
				// console.log(filename);
			});
			
			function UploadFile() {
				var reader = new FileReader();
				var file = document.getElementById('attach').files[0];
				reader.onload = function() {
					document.getElementById('fileContent').value = reader.result;
					document.getElementById('filename').value = file.name;
					document.getElementById('wizard').submit();
				}
				reader.readAsDataURL(file);
			}
		</script>
		<script src="{{ asset('assets/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
		@yield('foot')
	</body>
</html>