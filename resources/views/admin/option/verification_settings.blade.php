@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{__('Verification Settings Edit')}}</h4>
            </div>
            @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <form method="POST" action="{{ route('admin.option.verification.update', $verification_settings->id) }}" class="basicform">
              @csrf
              @method('PUT')
              @php 
                $verification_settings = json_decode($verification_settings->value);
              @endphp
              <div class="card-body">
                <div class="form-row">
                <div class="col-lg-6 col-md-4 col-sm-12">
                    <div class="form-group">
                    <div class="control-label">{{ __('Email verification') }}</div>
                    <label class="custom-switch mt-2">
                        <input {{ ($verification_settings->email_verification) ? 'checked' : '' }} type="checkbox" name="email_verification" class="custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">{{ __('On') }}</span>
                    </label>
                    </div>
                </div>
                <div class="col-lg-6 col-md-4 col-sm-12">
                    <div class="form-group">
                    <div class="control-label">{{ __('Phone verification') }}</div>
                    <label class="custom-switch mt-2">
                        <input {{ ($verification_settings->phone_verification) ? 'checked' : '' }} type="checkbox" name="phone_verification" class="custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">{{ __('On') }}</span>
                    </label>
                    </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn">{{ __('Submit') }}</button>
                  </div>
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection



