<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="app" id="app">
    <alert></alert>
    <div class="container" id="app-container">
        @if (auth()->check())
            <sidebar></sidebar>
        @endif
        <div class="content gray-bg">
            @yield('content')
        </div>
    </div>
    <!-- JavaScripts -->
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    <script src="http://maps.googleapis.com/maps/api/js?libraries=places&key={{ config('services.google.maps_api_key') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script>
        var config = {
            user: {!! auth()->user()->load('settings') !!},
            csrfToken: "{{ csrf_token() }}",
            url: "{{ url('/') }}",
            avatarPath: '/users',
            googleMapsApiKey: "{{ config('services.google.maps_api_key') }}"
        };
    </script>
    <script src="/js/app.js"></script>
</body>
</html>
