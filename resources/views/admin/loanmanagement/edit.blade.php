@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{__('Loan Package Edit')}}</h4>
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
            <form method="POST" action="{{ route('admin.loan.update', $loan_edit->id) }}" class="basicform">
              @csrf
              @method('put')
              <div class="card-body">
                <div class="form-group">
                  <label>{{ __('Name') }}</label>
                  <input type="text" class="form-control" name="name" placeholder="Name" required value="{{ $loan_edit->name }}">
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Minimum Amount') }}</label>
                        <input type="number" step="any" class="form-control" name="min_amount" placeholder="Minimum Amount" required value="{{ $loan_edit->min_amount }}">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Maximum Amount') }}</label>
                      <input type="number" step="any" class="form-control" name="max_amount" placeholder="Maximum Amount" required value="{{ $loan_edit->max_amount }}">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>{{ __('Transaction Charge') }}</label>
                  <select name="charge_type" class="form-control" id="charge_type">
                    <option value="">{{ __('Select charge type') }}</option>
                    <option value="fixed" {{ $loan_edit->charge_type == 'fixed' ? 'selected' : '' }}>{{ __('Fixed') }}</option>
                    <option value="percentage" {{ $loan_edit->charge_type == 'percentage' ? 'selected' : '' }}>{{ __('Percentage') }}</option>
                   
                  </select>
                </div>
              
                <div class="form-row">
                  <div class="transaction_fixed col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Fixed Amount') }}</label>
                      <input type="number" step="any" class="form-control" name="fix_charge" placeholder="Fixed Amount" value="{{ ($loan_edit->charge_type == 'fixed' || $loan_edit->charge_type == 'both') ? $loan_edit->fixed_charged : '' }}">
                     </div>
                </div>
                <div class="transaction_percentage col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                   <label>{{ __('Percentage Amount') }}</label>
                   <input type="number" step="any" class="form-control" name="percent_charge" placeholder="Percentage Amount" value="{{ ($loan_edit->charge_type == 'percentage' || $loan_edit->charge_type == 'both') ? $loan_edit->percent_charged : '' }}">
                  </div>
                </div>
              </div>
                
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Processing Time') }}</label>
                        <input type="number" step="any" class="form-control" name="duration" value="{{ $loan_edit->duration }}" placeholder="Processing Time" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Status') }}</label>
                      <select name="status" class="form-control">
                        <option value="">{{ __('Select Status') }}</option>
                        <option value="1" {{ ($loan_edit->status == '1') ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="0" {{ ($loan_edit->status == '0') ? 'selected' : '' }}>{{ __('Deactive') }}</option>
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
<input type="hidden" id="charge_type_data" value="{{ $loan_edit->charge_type }}">
@endsection
