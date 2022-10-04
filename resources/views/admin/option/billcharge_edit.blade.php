@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{ __('Bill Charge Edit') }}</h4>
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
            <form method="POST" action="{{ route('admin.option.billcharge.update', $billcharge->id) }}" class="basicform">
              @csrf
              @method('PUT')
              @php 
                $billcharge = json_decode($billcharge->value);
              @endphp
              <div class="card-body">
                <div class="form-row">
                 <div class="col-lg-12">
                    <div class="form-group">
                        <label>{{ __('Charge Type') }}</label>
                        <select name="charge_type" class="form-control" id="charge_type">
                          <option value="">{{ __('Select charge type') }}</option>
                          <option value="fixed" {{ $billcharge->charge_type == 'fixed' ? 'selected' : '' }}>{{ __('Fixed') }}</option>
                          <option value="percentage" {{ $billcharge->charge_type == 'percentage' ? 'selected' : '' }}>{{ __('Percentage') }}</option>
                        </select>
                    </div>
                 </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Sender Charge') }}</label>
                        <input type="number" step="any" class="form-control" value="{{ $billcharge->sender_charge }}" name="sender_charge" placeholder="Sender Charge">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Receiver Charge') }}</label>
                      <input type="number" step="any" class="form-control" value="{{ $billcharge->receiver_charge }}"  name="receiver_charge" placeholder="Receiver Charge">
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
@endsection



