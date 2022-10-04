@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{__('Others Bank Edit')}}</h4>
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
            <form method="POST" action="{{ route('admin.others-bank.update', $bank->id) }}" class="basicform_with_reload">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Bank Name') }}</label>
                      <input type="text" class="form-control" value="{{ $bank->name }}" name="name" placeholder="Bank Name" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Country') }}</label>
                        <select name="country" class="form-control">
                          <option value="">-- {{ __('Select Country') }} --</option>
                          @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ $bank->term_id == $country->id ? 'selected' : ''}}>{{ $country->title }}</option>
                          @endforeach
                        </select>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Minimum Amount') }}</label>
                        <input type="number" step="any" class="form-control" value="{{ $bank->min_amount }}" name="min_amount" placeholder="Minimum Amount" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Maximum Amount') }}</label>
                      <input type="number" step="any" class="form-control" value="{{ $bank->max_amount }}"  name="max_amount" placeholder="Maximum Amount" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>{{ __('Transaction Charge') }}</label>
                  <select name="charge_type" class="form-control" id="charge_type">
                    <option value="" >{{ __('Select charge type') }}</option>
                    <option value="fixed" {{ $bank->charge_type == 'fixed' ? 'selected' : '' }}>{{ __('Fixed') }}</option>
                    <option value="percentage" {{ $bank->charge_type == 'percentage' ? 'selected' : '' }}>{{ __('Percentage') }}</option>
                    <option value="both" {{ $bank->charge_type == 'both' ? 'selected' : '' }}>{{ __('Both') }}</option>
                  </select>
                </div>
                <div class="form-row">
                  <div class="transaction_fixed col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Fixed Amount') }}</label>
                      <input type="number" step="any" class="form-control" name="fix_charge" value="{{ ($bank->charge_type == 'fixed' || $bank->charge_type == 'both') ? $bank->fix_charge : '' }}" placeholder="Fixed Amount">
                    </div>
                </div>
                <div class="transaction_percentage col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                    <label>{{ __('Percentage Amount') }}</label>
                    <input type="number" step="any" class="form-control" name="percent_charge" value="{{ ($bank->charge_type == 'percentage' || $bank->charge_type == 'both') ? $bank->percent_charge : ''}}"  placeholder="Percentage Amount">
                  </div>
                </div>
              </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Processing Time') }}</label>
                        <input type="number" class="form-control" value="{{ $bank->period }}" name="period" placeholder="Processing Time" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Status') }}</label>
                      <select name="status" class="form-control">
                        <option value="">{{ __('Select Status') }}</option>
                        <option value="1" {{ $bank->status == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="0" {{ $bank->status == '0' ? 'selected' : '' }}>{{ __('Deactive') }}</option>
                      </select>
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
<input type="hidden" id="charge_type_data" value="{{ $bank->charge_type }}">
@endsection



