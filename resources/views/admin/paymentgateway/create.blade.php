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
        <form method="POST" action="{{ route('admin.payment.gateway.store') }}" enctype="multipart/form-data" class="basicform_with_reset">
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
            <div class="form-row">
              <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group">
                    <label>{{ __('Rate (1USD= ? USD)') }}</label>
                    <input type="number" step="any"  class="form-control" placeholder="Rate (1USD= ? USD)" required name="rate">
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
            </div>
            <div class="form-row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                      <label>{{ __('Minimum Amount') }}</label>
                      <input type="number" step="any" class="form-control" placeholder="Minimum Amount" required name="min_amount">
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                    <label>{{ __('Maximum Amount') }}</label>
                    <input type="number" step="any" class="form-control" placeholder="Maximum Amount" required name="max_amount">
                  </div>
                </div>
            </div>
            <div class="form-row">
              <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                      <label>{{ __('Deposit Charge') }}</label>
                      <select name="charge_type" class="form-control" id="charge_type">
                        <option value="">{{ __('Select charge type') }}</option>
                        <option value="fixed">{{ __('Fixed') }}</option>
                        <option value="percentage">{{ __('Percentage') }}</option>
                        <option value="both">{{ __('Both') }}</option>
                      </select>
                    </div>
                    <!--- Transaction Charge Fixed --->
                  <div class="form-row">
                      <div class="transaction_fixed col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ __('Fixed Amount') }}</label>
                          <input type="number" step="any" class="form-control" name="fixed_charge" placeholder="Fixed Amount">
                          </div>
                      </div>
                    <!--- Transaction Charge percentage --->
                    <div class="transaction_percentage col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Percentage Amount') }}</label>
                        <input type="number" step="any" class="form-control" name="percent_charge" placeholder="Percentage Amount">
                      </div>
                    </div>
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
