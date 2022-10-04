@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{ __('Branch Edit') }}</h4>
            </div>
            <form method="POST" action="{{ route('admin.branch.update', $branch->id) }}" class="basicform">
              @csrf
              @method('put')

              <div class="card-body">
                <div class="form-row">
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Branch Name') }}</label>
                        <input type="text" class="form-control" placeholder="Branch Name" required name="branch_name" value="{{ $branch->name }}">
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Phone Number') }}</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              <i class="fas fa-phone"></i>
                            </div>
                          </div>
                          <input type="nmuber" class="form-control phone-number" placeholder="Phone Number" required name="phone" value="{{ $branch->phone }}">
                        </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Email') }}</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                          </div>
                        </div>
                        <input type="email" class="form-control" placeholder="Email" required name="email" value="{{ $branch->email }}">
                      </div>
                    </div>
                  </div>        
                </div>
  
  
                <!--city-->
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('City') }}</label>
                        <input type="text" class="form-control" placeholder="City" required name="city" value="{{ $branch->city }}">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Address') }}</label>
                        <input type="text" class="form-control" placeholder="Address" required name="address" value="{{ $branch->address }}">
                    </div> 
                  </div>

                </div>
                <!--postal code and status-->
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Postal Code') }}</label>
                        <input type="text" class="form-control" placeholder="Postal Code" required name="postal_code" value="{{ $branch->postal_code }}">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Status') }}</label>
                        <select class="form-control selectric" name="status">
                          <option value="1" {{ $branch->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                          <option value="0" {{ $branch->status == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>
                    </div> 
                  </div>
                </div>
                <!-- <div class="form-group">
                  <label>{{ __('Postal Code') }}</label>
                  <input type="number" class="form-control" placeholder="Postal Code" required name="postal_code" value="{{ $branch->postal_code }}">
                </div> -->
                <div class="form-group">
                  <label>{{ __('Description(Optional)') }}</label>
                  <textarea class="form-control" cols="30" rows="3" placeholder="Description(Optional)" name="description" value="{{ $branch->description }}">
                      {{ $branch->description }}
                  </textarea>
                </div>
                <!-- <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Status') }}</label>
                        <select name="status" class="form-control">
                          <option disabled selected>-- {{ __('Select Status') }} --</option>
                          <option {{ ($branch->status == 'active')? 'selected': '' }} value="active">{{ __('Active') }}</option>
                            ">{{ __('Active') }}</option>
                          <option {{ ($branch->status == 'inactive')? 'selected': '' }} value="inactive">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                  </div>

                </div> -->
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
@endsection


