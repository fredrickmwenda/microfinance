@extends('layouts.backend.app')

@push('css')
  <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/select2.min.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Withdraw Method Create') }}</h4>
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
            <form method="POST" action="{{ route('admin.withdraw.store') }}" class="basicform_with_reset">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label>{{ __('Method Name') }}</label>
                  <input type="text" class="form-control" placeholder="Method Name" required name="name">
                </div>
                <div class="form-row">
                  <div class="col-lg-12 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Select Currency') }}</label>
                        <select name="currency[]" class="form-control select2" multiple="multiple">
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->title }}</option>
                            @endforeach
                        </select>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Minimum Amount') }}</label>
                        <input type="number" class="form-control" placeholder="Minimum Amount" required name="min_amount">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Maximum Amount') }}</label>
                      <input type="number" class="form-control" placeholder="Maximum Amount" required name="max_amount">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>{{ __('Charge Type') }}</label>
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
                      <input type="number" class="form-control" name="fixed_charge" placeholder="Fixed Amount">
                     </div>
                  </div>
                <!--- Transaction Charge percentage --->
                <div class="transaction_percentage col-lg-6 col-md-6 col-sm-12">
                  <div class="form-group">
                   <label>{{ __('Percentage Amount') }}</label>
                   <input type="number" class="form-control" name="percent_charge" placeholder="Percentage Amount">
                  </div>
                </div>
              </div>
                <div class="form-row">
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Status') }}</label>
                      <select name="status" class="form-control">
                        <option value="">{{ __('Select Status') }}</option>
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Deactive') }}</option>
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

@push('js')
  <script src="{{ asset('backend/admin/assets/js/select2.min.js') }}"></script>
@endpush