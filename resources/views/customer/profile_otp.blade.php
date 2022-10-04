<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('APP_NAME') }}</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/components.css') }}">
</head>

<body>
  <div id="app">
    <section class="section">
        <div class="container mt-5">
          <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
              <div class="login-brand">
             
              </div>
              <div class="card card-primary">
                <div class="card-header"><h4>{{ __('Account Verification') }}</h4></div>
                 <div class="card-body">
                    @if (Session::has('message'))
                    <div class="alert alert-danger">{{ Session::get('message') }}</div>
                   @endif
                   <form method="POST" action="{{ route('profile.otp.confirmation') }}" class="needs-validation" novalidate="">
                   @csrf
                   <div class="login-section">
                       <h6>{{ __('OTP') }}</h6>
                       <div class="form-group">
                           <input type="text" placeholder="Enter OTP" class="form-control" name="otp">
                       </div>  
                       </div>
                       <div class="row">
                           <div class="col-lg-12">
                               <div class="login-btn">
                                   <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                               </div>
                           </div>
                       </div>
                   </div>
                   </form>
                    <form method="POST" action="{{ route('profile.otp.resend') }}" class="needs-validation" novalidate="">
                    @csrf
                    <div class="row text-right">
                        <div class="col-lg-12">
                            <div class="login-btn">
                                <button class="btn btn-link" type="submit">{{ __('Resend OTP') }}</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                 </div>
              </div>
            </div>
          </div>
        </div>
      </section>
  </div>
</body>
</html>
