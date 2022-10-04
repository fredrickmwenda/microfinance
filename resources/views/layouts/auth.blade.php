<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <title></title>

    <!-- Scripts -->
    <script src="{{ asset('assets/auth/js/app.js') }}" defer></script>

    <!-- Google font -->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('assets/auth/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/backend/admin/assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/auth/css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>

	@yield('js-script')

    @stack('js')

    <!-- jQuery -->
    <script src="{{ asset('assets/backend/admin/assets/js/jquery-3.5.1.min.js') }}" ></script>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/backend/admin/assets/js/bootstrap.min.js') }}" ></script>
    <script text="javascript">
    $(document).ready(function () {
        $(".toggle-password").click(function () {
            event.preventDefault();
            console.log('clicked');
            $("#show_hide_password i").toggleClass("fa-eye fa-eye-slash");
            if($("#show_hide_password input").attr("type") == "text"){
                $("#show_hide_password input").attr("type", "password");
            }else if($("#show_hide_password input").attr("type") == "password"){
                $("#show_hide_password input").attr("type", "text");
            }
        });
    });
</script>

    </script>



</body>
</html>
