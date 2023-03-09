<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title')</title>
	<!--font-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet" />
	@vite('resources/css/normalize.css')
	@vite('resources/css/tailwind.css')
	@vite('resources/sass/app.scss')
	@vite('resources/js/app.js')
</head>

<body>
	<div class="loader-bg">
    	<div class="loader">
			<span class="loader-inner"></span>
    	</div>
 	</div>
	<header class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-[#0d1117] dark:border-gray-700">
		<div class="px-3 py-3 lg:px-5 lg:pl-3">
			<div class="flex items-center justify-between">
				<div class="flex items-center justify-start">
					<a href="{{ route('index') }}" class="flex ml-2 mr-2">
						<h1><i class="las la-hippo"></i>すぴろぐ</h1>
					</a>
				</div>
				<div class="flex items-center">
					<div class="flex items-center ml-3">
						<div class="flex items-center gap-5">
						<i class="las la-campground"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

	@yield('content')
	<footer>

	</footer>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	@yield('js')
</body>
</html>