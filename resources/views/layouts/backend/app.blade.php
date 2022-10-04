<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ env('APP_NAME') }}</title>
     <link rel="icon" href="{{ asset('assets/uploads/favicon.ico') }}">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/backend/admin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/admin/assets/css/all.min.css') }}">
    <!--dropify-->
    <link rel="stylesheet" href="{{ asset('assets/backend/assets/plugins/dropify/css/dropify.min.css') }}">    
    <!--select 2-->
    <link rel="stylesheet" href="{{ asset('assets/backend/admin/assets/css/select2.min.css') }}">
    <link href="{{ asset('assets/backend/admin/assets/css/toastr.min.css') }}" rel="stylesheet" type="text/css" />


    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/backend/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/admin/assets/css/components.css') }}">

    <!--Datatable-->
    <link href="{{ asset('assets/backend/admin/assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @stack('css')
    <!-- <style>


    </style> -->
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <!--- Header Section ---->
      @include('layouts.backend.partials.header')

      <!--- Sidebar Section --->
      @include('layouts.backend.partials.sidebar')

      <!--- Main Content --->
      <div class="main-content">
        <section class="section">
         @yield('head')
       </section>
      @yield('content')
      </div>

     <!--- Footer Section --->
     @include('layouts.backend.partials.footer')
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="{{ asset('assets/backend/admin/assets/js/jquery-3.5.1.min.js') }}" ></script>
  <script src="{{ asset('assets/backend/admin/assets/js/popper.min.js') }}" ></script>
  <script src="{{ asset('assets/backend/admin/assets/js/bootstrap.min.js') }}" ></script>
  <script src="{{ asset('assets/backend/admin/assets/js/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('assets/backend/admin/assets/js/moment.min.js') }}"></script>
  <!--dropify-->
  <script src="{{ asset('assets/backend/assets/plugins/dropify/js/dropify.min.js') }}"></script>
  <!--dropify multiple-->
  <script src="{{ asset('assets/backend/assets/plugins/dropify/js/dropify-multiple.js') }}"></script>
  <script src="{{ asset('assets/backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
  <script src="{{ asset('assets/backend/admin/assets/js/toastr.min.js') }}"></script>
      <!-- Toastr-->
      <!-- <link href="{{ asset('assets/backend/libs/toastr/build/toastr.min.css') }}" rel="stylesheet" type="text/css" /> -->
  <!-- Template JS File -->
  <script src="{{ asset('assets/backend/admin/assets/js/scripts.js') }}"></script>
  <!-- <script src="{{ asset('assets/backend/admin/assets/js/form.js') }}"></script> -->
  <!--select2-->
  <script src="{{ asset('assets/backend/admin/assets/js/select2.min.js') }}"></script>

  <script src="{{ asset('assets/backend/admin/assets/js/datatables.min.js') }}"></script>
  <!--datatables bootstrap-->
  <script src="{{ asset('assets/backend/admin/assets/js/datatables.bootstrap4.min.js') }}"></script>



  <!-- Page Specific JS File -->
  @stack('js')
  <script src="{{ asset('assets/backend/admin/assets/js/custom.js') }}"></script>

  <!--alert-->

  @if (isset($errors) && $errors->any())
  
    @foreach ($errors->all() as $error)
    <script>
      $(document).ready(function() {
        toastr.error('{{ $error }}', 'Error', {
          closeButton: true,
          progressBar: true,
        });
        // toastr.error('{{ $error }}', 'Error!')
      });
    </script>
    @endforeach
  
  @endif

  @if (session('message'))
        <script>
            $(document).ready(function() {
                toastr.success('{{ session('message') }}', 'Success!')
            });
        </script>
  @endif

  @if (session('error'))
        <script>
            $(document).ready(function() {
                toastr.error('{{ session('error') }}', 'Error!')
            });
        </script>
  @endif


  
</body>
</html>
