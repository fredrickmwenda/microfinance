@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{__('Own Bank Charge Edit')}}</h4>
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
            <form method="POST" action="{{ route('admin.option.ownbank.update', $ownbank->id) }}" class="basicform_with_reload">
              @csrf
              @method('PUT')
              @php 
                $ownbank = json_decode($ownbank->value);
              @endphp
              <div class="card-body">
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Minimum Amount') }}</label>
                        <input type="number" step="any" class="form-control" value="{{ $ownbank->min_amount }}" name="min_amount" placeholder="Minimum Amount" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Maximum Amount') }}</label>
                      <input type="number" step="any" class="form-control" value="{{ $ownbank->max_amount }}"  name="max_amount" placeholder="Maximum Amount" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>{{ __('Transaction Charge') }}</label>
                  <select name="charge_type" class="form-control" id="charge_type">
                    <option value="" >{{ __('Select charge type') }}</option>
                    <option value="fixed" {{ $ownbank->charge_type == 'fixed' ? 'selected' : '' }}>{{ __('Fixed') }}</option>
                    <option value="percentage" {{ $ownbank->charge_type == 'percentage' ? 'selected' : '' }}>{{ __('Percentage') }}</option>
                    <option value="both" {{ $ownbank->charge_type == 'both' ? 'selected' : '' }}>{{ __('Both') }}</option>
                  </select>
                </div>
            
                <div class="form-row">
                  <div class="transaction_fixed col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Fixed Amount') }}</label>
                      <input type="number" step="any" class="form-control" name="fixed_charge" value="{{ ($ownbank->charge_type == 'fixed' || $ownbank->charge_type == 'both') ? $ownbank->fixed_charge : '' }}" placeholder="Fixed Amount">
                      </div>
                </div>
                <div class="transaction_percentage col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                    <label>{{ __('Percentage Amount') }}</label>
                    <input type="number" step="any" class="form-control" name="percent_charge" value="{{ ($ownbank->charge_type == 'percentage' || $ownbank->charge_type == 'both') ? $ownbank->percent_charge : ''}}"  placeholder="Percentage Amount">
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
<input type="hidden" id="charge_type_data" value="{{ $ownbank->charge_type }}">
@endsection



