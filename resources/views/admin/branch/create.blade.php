@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{ __('Branch Create') }}</h4>
            </div>
            @if ($errors->any())
              <div class="alert alert-danger">
                  <strong>{{ __('woops!') }}</strong> {{ __('There were some problems with your input.') }}<br><br>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <form method="POST" action="{{ route('admin.branch.store') }}" class="basicform_with_reset">
              @csrf
              <div class="card-body">
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Branch Name') }}</label>
                        <input type="text" class="form-control" placeholder="Branch Name" required name="branch_name">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Phone Number') }}</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              <i class="fas fa-phone"></i>
                            </div>
                          </div>
                          <input type="nmuber" class="form-control phone-number" placeholder="Phone Number" required name="phone">
                        </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>{{ __('Email Address') }}</label>
                  <input type="email" class="form-control" placeholder="Email Address" required name="email_address">
                </div>
                <div class="form-group">
                  <label>{{ __('Address') }}</label>
                  <textarea class="form-control" cols="30" rows="4" placeholder="Address" required name="address"></textarea>
                </div>

                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Postal Code') }}</label>
                      <input type="number" class="form-control" placeholder="Postal Code" required name="postal_code">  
                    </div>
                  </div>
                  
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('City') }}</label>
                      <input type="text" class="form-control" placeholder="City" required name="city">
                    </div>
                  </div>
                </div>


                <div class="form-group">
                  <label>{{ __('Description(Optional)') }}</label>
                  <textarea class="form-control" cols="30" rows="4" placeholder="Description(Optional)" name="description"></textarea>                
                </div>          
                  
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Status * Is the Branch already setup') }}</label>
                        <select name="status" class="form-control" required>
                          <option disabled selected>-- {{ __('Select Status') }} --</option>
                          <option value="active">{{ __('Active') }}</option>
                          <option value="inactive">{{ __('In-Active') }}</option>
                        </select>
                    </div>
                  </div>
                  <!-- <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Is Featured') }}</label>
                        <select name="is_featured" class="form-control" required>
                          <option disabled selected>-- {{ __('Select Featured') }} --</option>
                          <option value="1">{{ __('Yes') }}</option>
                          <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                  </div> -->
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



