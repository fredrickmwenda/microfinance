@extends('layouts.auth')

@section('content')
<div class="auth-container d-md-flex align-items-center">
    <div class="container">
        <div class="row ">
            <div class="col-md-12 col-lg-10 offset-lg-1">
                <div class="bg-white p-2">
                    <div class="row no-gutters">
                        <div class="col-md-6">
                            <div class="card card-signin">
                               

                                <div class="card-body">
                                <img class="logo" src="">
                                <h6 class="py-4">{{ __('Reset Password') }}</h6>
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf

                                        <div class="row mb-3">
                                            <!-- <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label> -->

                                            <div class="col-md-12">
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter Your Email"  required autocomplete="email" autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-0">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn btn-login btn-block btn-primary">
                                                    {{ __('Send Password Reset Link') }}
                                                </button>
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
                                    <h2 class="font-weight-bold">Mweguni Enterprises</h2>

                                    <div class="divider"></div>

                                    <p>Enter your registered email for getting password reset link</p> 
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
