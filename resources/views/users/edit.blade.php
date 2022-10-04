@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Edit User') }}</h4>
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
            <form method="POST" action="{{ route('admin.users.update', $user_edit->id) }}" class="basicform">
              @csrf
              @method('put')
              <div class="card-body">
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control" placeholder="Name" required name="name" value="{{ $user_edit->name }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Email') }}</label>
                        <input type="email" class="form-control" placeholder="Email Address" required name="email" value="{{ $user_edit->email }}">
                      </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                          <label>{{ __('Phone') }}</label>
                          <input type="text" class="form-control" placeholder="Phone" required name="phone_number" value="{{ $user_edit->phone }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Password') }}</label>
                        <input type="password" class="form-control" placeholder="Password" name="password">
                      </div>
                    </div>
                </div>
                <div class="form-group">
                  <div class="custom-file mb-3">
                    <label>{{ __('Status') }}</label>
                    <select name="status" class="form-control">
                      <option>-- {{ __('Select Status') }} --</option>
                      <option value="1" {{ ($user_edit->status == 1) ? 'selected' : '' }}>{{ __('Active') }}</option>
                      <option value="0" {{ ($user_edit->status == 0) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="control-label">{{ __('Email verify status') }}</div>
                      <label class="custom-switch mt-2">
                        <input {{ ($user_edit->email_verified_at) ? 'checked' : '' }} type="checkbox" name="email_verified_at" class="custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">{{ __('verify') }}</span>
                      </label>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="control-label">{{ __('Phone verify status') }}</div>
                      <label class="custom-switch mt-2">
                        <input {{ ($user_edit->phone_verified_at) ? 'checked' : '' }} type="checkbox" name="phone_verified_at" class="custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">{{ __('verify') }}</span>
                      </label>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <div class="control-label two_step_auth">{{ __('2 Step Auth') }}</div>
                      <label class="custom-switch mt-2">
                        <input {{ ($user_edit->two_step_auth) ? 'checked' : '' }} type="checkbox" name="two_step_auth" class="custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">{{ __('Enable') }}</span>
                      </label>
                    </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn">{{ __('Update') }}</button>
                  </div>
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection

