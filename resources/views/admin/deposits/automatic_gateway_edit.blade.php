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
            <form method="POST" action="{{ route('admin.deposit.automatic.gateway.update', $gateway->id) }}" enctype="multipart/form-data" class="basicform_with_reload">
              @csrf
              @method('PUT')
              @php
                  $info_data = json_decode($gateway->data);
              @endphp
              <div class="card-body">
                <div class="form-row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                      <div class="form-group">
                          <label>{{ __('Name') }}</label>
                          <input type="text" class="form-control" placeholder="Name" required name="name" value="{{ $gateway->name }}">
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Logo') }}</label>
                        <input type="file" class="form-control" name="logo">
                        <br>
                        <img width="100" src="{{ asset($gateway->logo) }}" alt="{{ $gateway->logo }}">
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                      <div class="form-group">
                          <label>{{ __('Status') }}</label>
                          <select name="status" class="form-control" >
                            <option value="1" @if($gateway->status == 1) selected @endif>{{ __('Active') }}</option>
                            <option value="0" @if($gateway->status == 0) selected @endif>{{ __('Disable') }}</option>
                          </select>
                      </div>
                    </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Rate') }} 
                          @switch($gateway->id)
                              @case(4) (1 USD = ? INR)   @break
                              @case(5) (1 USD = ? RM)    @break
                              @case(7) (1 USD = ? GHS)  @break
                              @default 
                                (1 USD = ? USD)
                          @endswitch
                        </label>
                        <input type="number" step="any" class="form-control" placeholder="Rate (1USD= ? USD)" required name="rate" value="{{ $gateway->rate }}">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Minimum Amount') }}</label>
                        <input type="number" step="any" class="form-control" placeholder="Minimum Amount" required name="deposit_min" value="{{ $gateway->deposit_min }}">
                    </div>
                  </div>
                </div>
                <div class="form-row">
                    
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Maximum Amount') }}</label>
                        <input type="number" step="any" class="form-control" placeholder="Maximum Amount" required name="deposit_max" value="{{ $gateway->deposit_max }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Deposit Charge') }}</label>
                            
                            <select name="charge_type" class="form-control" id="charge_type">
                              <option value="">{{ __('Select charge type') }}</option>
                              <option value="fixed" {{ $gateway->charge_type == 'fixed' ? 'selected' : '' }}>{{ __('Fixed') }}</option>
                              <option value="percentage" {{ $gateway->charge_type == 'percentage' ? 'selected' : '' }}>{{ __('Percentage') }}</option>
                              <option value="both" {{ $gateway->charge_type == 'both' ? 'selected' : '' }}>{{ __('Both') }}</option>
                            </select>
                          </div>
                          <!--- Transaction Charge Fixed --->
                          <div class="form-row">
                            <div class="transaction_fixed col-lg-6 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label>{{ __('Fixed Amount') }}</label>
                                <input type="number" step="any" class="form-control" name="fix_charge" placeholder="Fixed Amount" value="{{ ($gateway->charge_type == 'fixed' || $gateway->charge_type == 'both') ? $gateway->fix_charge : '' }}">
                               </div>
                          </div>
                          <!--- Transaction Charge percentage --->
                          <div class="transaction_percentage col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                             <label>{{ __('Percentage Amount') }}</label>
                             <input type="number" step="any" class="form-control" name="percent_charge" placeholder="Percentage Amount" value="{{ ($gateway->charge_type == 'percentage' || $gateway->charge_type == 'both') ? $gateway->percent_charge : '' }}">
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    @foreach ($info_data as $key => $data)
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label>{{ ucwords(str_replace("_"," ",$key)) }}</label>
                          <input type="text" class="form-control" placeholder="" required name="data[{{$key}}]" value="{{ $data }}">
                        </div>
                    </div>
                    @endforeach
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
<input type="hidden" id="charge_type_data" value="{{ $gateway->charge_type }}">
@endsection