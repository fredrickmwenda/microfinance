@extends('layouts.backend.app')

@push('css')
  <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/select2.min.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Manual Payment Gateway Edit') }}</h4>
            </div>
            <form method="POST" action="{{ route('admin.manual_gateway.update', $manualGateway->id) }}" class="basicform">
              @csrf
              @method('put')
              <div class="card-body">
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                          <label>{{ __('Payment Method Name') }}</label>
                          <input type="text" name="name" class="form-control" placeholder="Payment Method Name" required value="{{ $manualGateway->name }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Select Currency') }}</label>
                        <select name="currency[]" class="form-control select2" multiple="multiple">
                          @foreach ($currencies as $currency)
                          @php $select = '' @endphp
                          @foreach ($manualGateway->term as $value)
                            @if ($value->id == $currency->id)
                              @php $select = 'selected' @endphp
                            @endif   
                          @endforeach
                              <option value="{{ $currency->id }}" {{ $select }}>{{ $currency->title }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Minimum Amount') }}</label>
                        <input type="number" step="any" name="deposit_min" class="form-control" placeholder="Minimum Amount" required value="{{ $manualGateway->deposit_min }}">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Maximum Amount') }}</label>
                      <input type="number" step="any" name="deposit_max" class="form-control" placeholder="Maximum Amount" required value="{{ $manualGateway->deposit_max }}">
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Charge Type') }}</label>
                      <select name="charge_type" class="form-control" id="charge_type">
                        <option value="">{{ __('Select charge type') }}</option>
                        <option value="fixed" {{ ($manualGateway->charge_type == 'fixed') ? 'selected' : '' }}>{{ __('Fixed') }}</option>
                        <option value="percentage" {{ ($manualGateway->charge_type == 'percentage') ? 'selected' : '' }}>{{ __('Percentage') }}</option>
                        <option value="both" {{ ($manualGateway->charge_type == 'both') ? 'selected' : '' }}>{{ __('Both') }}</option>
                      </select>
                    </div>
                    <div class="form-row">
                      <!--- Transaction Charge Fixed --->
                    <div class="transaction_fixed col-lg-6">
                      <div class="form-group">
                       <label>{{ __('Fixed Amount') }}</label>
                       <input type="numbers" step="any" name="fix_charge" class="form-control" placeholder="Fixed Amount" value="{{ $manualGateway->fix_charge }}">
                      </div>
                    </div>
                    <!--- Transaction Charge Percentage --->
                    <div class="transaction_percentage col-lg-6">
                      <div class="form-group">
                       <label>{{ __('Percentage Amount') }}</label>
                       <input type="numbers" step="any" name="percent_charge" class="form-control" placeholder="Percentage Amount" value="{{ $manualGateway->percent_charge }}">
                      </div>
                    </div>
                    </div>
                    
                  </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Payment Method Logo') }}</label>
                        <input type="file" name="logo" class="form-control">
                        <img width="100" src="{{ asset($manualGateway->logo) }}" alt="{{ $manualGateway->logo }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Status') }}</label>
                            <select name="status" class="form-control">
                              <option value="">{{ __('Select Status') }}</option>
                              <option value="1" {{ ($manualGateway->status == 1) ? 'selected' : '' }}>{{ __('Active') }}</option>
                              <option value="0" {{ ($manualGateway->status == 0) ? 'selected' : '' }}>{{ __('Deactive') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label>{{ __('Account Information') }}</label>
                  <textarea class="form-control" name="data" id="" cols="30" rows="10">{{ $manualGateway->data }}</textarea>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="basicbtn btn btn-primary btn-lg float-right w-100">{{ __('Submit') }}</button>
                  </div>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
<input type="hidden" id="charge_type_data" value="{{ $manualGateway->charge_type }}">
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/select2.min.js') }}"></script>
@endpush
