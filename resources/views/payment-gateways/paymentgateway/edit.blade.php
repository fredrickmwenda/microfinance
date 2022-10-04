@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Payment Gateway Edit') }}</h4>
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
            <form method="POST" action="{{ route('admin.payment.gateway.update', $pg_edit_data->id) }}" enctype="multipart/form-data" class="basicform">
              @csrf
              @method('PUT')
              @php
                  $info_data = json_decode($pg_edit_data->paymentMeta->value);
              @endphp
              <div class="card-body">
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                          <label>{{ __('Name') }}</label>
                          <input type="text" class="form-control" placeholder="Name" required name="name" value="{{ $pg_edit_data->title }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Logo') }}</label>
                        <input type="file" class="form-control" required name="logo">
                        <br>
                        <img width="100" src="{{ asset($info_data->logo) }}" alt="{{ $info_data->logo }}">
                      </div>
                    </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Rate (1USD= ? USD)') }}</label>
                        <input type="number" step="any" class="form-control" placeholder="Rate (1USD= ? USD)" required name="rate" value="{{ $info_data->rate }}">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Select Payment Gateway') }}</label>
                      <select name="payment_gateway" class="form-control">
                        <option value="">{{ __('Select Payment Gateway') }}</option>
                        <option value="paypal" {{ ($info_data->payment_gateway == 'paypal') ? 'selected' : '' }}>{{ __('paypal') }}</option>
                        <option value="bkash" {{ ($info_data->payment_gateway == 'bkash') ? 'selected' : '' }}>{{ __('bkash') }}</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                          <label>{{ __('Minimum Amount') }}</label>
                          <input type="number" step="any" class="form-control" placeholder="Minimum Amount" required name="min_amount" value="{{ $info_data->min_amount }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Maximum Amount') }}</label>
                        <input type="number" step="any" class="form-control" placeholder="Maximum Amount" required name="max_amount" value="{{ $info_data->max_amount }}">
                      </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Deposit Charge') }}</label>
                            <select name="charge_type" class="form-control" id="charge_type">
                              <option value="">{{ __('Select charge type') }}</option>
                              <option value="fixed" {{ $info_data->charge_type == 'fixed' ? 'selected' : '' }}>{{ __('Fixed') }}</option>
                              <option value="percentage" {{ $info_data->charge_type == 'percentage' ? 'selected' : '' }}>{{ __('Percentage') }}</option>
                              <option value="both" {{ $info_data->charge_type == 'both' ? 'selected' : '' }}>{{ __('Both') }}</option>
                            </select>
                          </div>
                          <!--- Transaction Charge Fixed --->
                          <div class="form-row">
                            <div class="transaction_fixed col-lg-6 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label>{{ __('Fixed Amount') }}</label>
                                <input type="number" step="any" class="form-control" name="fixed_charge" placeholder="Fixed Amount" value="{{ ($info_data->charge_type == 'fixed' || $info_data->charge_type == 'both') ? $info_data->fixed_charge : '' }}">
                               </div>
                          </div>
                          <!--- Transaction Charge percentage --->
                          <div class="transaction_percentage col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                             <label>{{ __('Percentage Amount') }}</label>
                             <input type="number" step="any" class="form-control" name="percent_charge" placeholder="Percentage Amount" value="{{ ($info_data->charge_type == 'percentage' || $info_data->charge_type == 'both') ? $info_data->percent_charge : '' }}">
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Status') }}</label>
                            <select name="status" class="form-control">
                              <option value="">{{ __('Select Status') }}</option>
                              <option value="1" {{ ($pg_edit_data->status == 1) ? 'selected' : '' }}>{{ __('Active') }}</option>
                              <option value="0" {{ ($pg_edit_data->status == 1) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                            </select>
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
<input type="hidden" value="{{ $info_data->charge_type }}" id="charge_type_data">
@endsection
