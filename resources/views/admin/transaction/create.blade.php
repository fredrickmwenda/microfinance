@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{ __('Transaction Create') }}</h4>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label>{{ __('Default Input Text') }}</label>
                <input type="text" class="form-control">
              </div>
              <div class="form-group">
                <label>{{ __('Phone Number (US Format)') }}</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-phone"></i>
                    </div>
                  </div>
                  <input type="text" class="form-control phone-number">
                </div>
              </div>
              <div class="form-group">
                <label>{{ __('Password Strength') }}</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-lock"></i>
                    </div>
                  </div>
                  <input type="password" class="form-control pwstrength" data-indicator="pwindicator">
                </div>
                <div id="pwindicator" class="pwindicator">
                  <div class="bar"></div>
                  <div class="label"></div>
                </div>
              </div>
              <div class="form-group">
                <label>{{ __('Currency') }}</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      $
                    </div>
                  </div>
                  <input type="text" class="form-control currency">
                </div>
              </div>
              <div class="form-group">
                <label>{{ __('Purchase Code') }}</label>
                <input type="text" class="form-control purchase-code" placeholder="ASDF-GHIJ-KLMN-OPQR">
              </div>
              <div class="form-group">
                <label>{{ __('Invoice') }}</label>
                <input type="text" class="form-control invoice-input">
              </div>
              <div class="form-group">
                <label>{{ __('Date') }}</label>
                <input type="text" class="form-control datemask" placeholder="YYYY/MM/DD">
              </div>
              <div class="form-group">
                <label>{{ __('Credit Card') }}</label>
                <input type="text" class="form-control creditcard">
              </div>
              <div class="form-group">
                <label>{{ __('Tags') }}</label>
                <input type="text" class="form-control inputtags">
              </div>
            </div>
        </div>
    </div>
</div>
@endsection


