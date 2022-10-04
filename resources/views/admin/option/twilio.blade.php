@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{__('Twilio Edit')}}</h4>
            </div>
            @if ($errors->any())
              <div class="alert alert-danger">
                  <strong>{{ __('Whoops!') }}</strong> {{ __('There were some problems with your input.') }}<br><br>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <form method="POST" action="{{ route('admin.option.twilio.update', $twilio->id) }}" class="basicform_with_reload">
              @csrf
              @method('PUT')
              @php 
                $twilio = json_decode($twilio->value);
              @endphp
              <div class="card-body">
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Twilio SID') }}</label>
                        <input type="text" class="form-control" value="{{ $twilio->twilio_sid }}" name="twilio_sid" placeholder="Twilio SID" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Twilio Auth Token') }}</label>
                        <input type="text" class="form-control" value="{{ $twilio->twilio_auth_token }}" name="twilio_auth_token" placeholder="Twilio Auth Token" required>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-12 col-md-6 col-sm-12">
                      <div class="form-group">
                          <label>{{ __('Twilio Number') }}</label>
                          <input type="text" class="form-control" value="{{ $twilio->twilio_number }}" name="twilio_number" placeholder="Twilio Number" required>
                      </div>
                    </div>
                    <div class="col-lg-12 col-md-6 col-sm-12">
                      <div class="form-group">
                          <label>{{ __('Message Template') }}</label>
                          <textarea name="message" class="form-control">{{ $twilio->message }}</textarea>
                      </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn">Submit</button>
                  </div>
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection



