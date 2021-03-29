<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Please try again tomorrow</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="{{ asset('multilevel/thanks/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('multilevel/thanks/css/fontawesome-all.css') }}">
		<link rel="stylesheet" href="{{ asset('multilevel/thanks/css/style.css') }}">
	</head>
	<body>
		<section id="thank-you" class="thank-you-section">
			<div class="container">
				<div class="thank-you-wrapper position-relative thank-wrapper-style-one">
					<!-- <div class="thank-you-close text-center">x</div> -->
					<div class="thank-txt text-center">
						<div class="thank-icon">
							<img src="{{ asset('multilevel/thanks/img/error.png') }}" alt="" width="120">
						</div>
						<h1>Oops!!!</h1>
						<p>Please try again tomorrow.</p>
						<a class="d-block text-center text-uppercase" href="//swisscredit.ng">back to home</a>
					</div>
					<div class="th-bottom-vector position-absolute">
						<img src="{{ asset('multilevel/thanks/img/tv1.png') }}" alt="">
					</div>
				</div>
			</div>
		</section>
		<script src="{{ asset('multilevel/thanks/js/jquery-3.3.1.min.js') }}"></script>
		<script src="{{ asset('multilevel/thanks/js/bootstrap.min.js') }}"></script>
	</body>
</html>