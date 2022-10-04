@extends('layouts.backend.app')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
        <div class="card-header">
        <h4>{{ __('Payment Gateway Create') }}</h4>
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
        <form method="POST" action="{{ route('admin.payment-gateway.store') }}" enctype="multipart/form-data" class="basicform_with_reset">
          @csrf
          <div class="card-body">
            <div class="form-row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                      <label>{{ __('Name') }}</label>
                      <input type="text" class="form-control" placeholder="Name" required name="name">
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                    <label>{{ __('Logo') }}</label>
                    <input type="file" class="form-control" required name="logo">
                  </div>
                </div>
            </div>
            <!-- <div class="form-row">
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                    <label>{{ __('Paybill Number * for Mpesa') }}</label>
                    <input type="text" class="form-control" placeholder="Paybill Number" name="paybill_number">
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                  <label>{{ __('Select Payment Gateway') }}</label>
                  <select name="payment_gateway" class="form-control">
                    <option value="">{{ __('Select Payment Gateway') }}</option>
                    <option value="paypal">{{ __('paypal') }}</option>
                    <option value="bkash">{{ __('bkash') }}</option>
                  </select>
                </div>
              </div>
            </div> -->

            <div class="form-row">
              <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                      <label>{{ __('Client Key * for Paypal & Stripe & Mpesa') }}</label>
                      <input type="text" class="form-control" placeholder="Client Key" name="client_key">
                    </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12">     
                <div class="form-group">
                  <label>{{ __('Client Secret * for Paypal & Stripe & MPesa') }}</label>
                  <input type="text" class="form-control" placeholder="Client Secret" name="client_secret">
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                    <label>{{ __('Paybill Number * for Mpesa') }}</label>
                    <input type="text" class="form-control" placeholder="Paybill Number" name="paybill">
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                      <label>{{ __('Status') }}</label>
                      <select name="status" class="form-control">
                        <option value="">{{ __('Select Status') }}</option>
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Inactive') }}</option>
                      </select>
                  </div>
              </div>
            </div>
            <div class="form-group">
              <label>{{ __('Description') }}</label>
              <textarea name="description" class="form-control" rows="5"></textarea>
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
