@extends('layouts.auth')


@section('content')
<div class="auth-container d-md-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-10 offset-lg-1" >
                <div class="bg-white p-2">
                    <div class="row no-gutters">
                        <div class="col-md-6">          
                            <div class="card card-signin p-3 my-5">
                                <div class="card-body">
                                    <img class="logo" src="">

                                    <h5 class="text-center py-4">{{ __('Login') }}</h4>

                                    @if(Session::has('error'))
                                        <div class="alert alert-danger text-center">
                                            <strong>{{ session('error') }}</strong>
                                        </div>
                                    @endif





                                    <form method="POST" class="form-signin" action="{{ route('login') }}">
                                        @csrf

                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>

                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <!--append an eye icon to the password field to show or hide the password-->
                                                <div class="input-group" id="show_hide_password">
                                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text toggle-password" style="cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                                                        <!-- <i class="fa-solid fa-eye"></i> -->
                                                    </div>
                                                </div>

                                                <!-- <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Enter Password " required> -->

                                                @if ($errors->has('password'))
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <div class="custom-control custom-checkbox mb-3"style="float: left;" >
                                                    <input type="checkbox" name="remember" class="custom-control-input" id="remember" value="1" {{ old('remember') ? 'checked' : '' }} >
                                                    <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group row mb-0">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    {{ __('Login') }}
                                                </button>



                                        

                                            </div>
                                        </div>


                                        <div class="form-group row mt-3">
                                            <div class="col-md-12">
                                                <a class="btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Password?') }}
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 d-none d-md-block">
                                <div id="auth-bg" class="d-flex align-items-center justify-content-center">
                                    <div class="px-5">
                                        <p class="mb-1 font-weight-light">WELCOME TO</p>
                                        <h2 class="font-weight-bold">Mweguni Credit</h2>

                                        <div class="divider"></div>

                                        <p>Enter your registered email and password and login into your account</p>

                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('js')
    


@endpush