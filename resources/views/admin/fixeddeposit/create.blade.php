@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Fixed Deposit Create') }}</h4>
            </div>
            <form method="POST" action="{{ route('admin.fixed-deposit.store') }}" class="basicform_with_reset">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label>{{ __('Title') }}</label>
                  <input type="text" class="form-control" name="title" placeholder="Name" required>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Minimum Amount') }}</label>
                        <input type="number" step="any" class="form-control" name="min_amount" placeholder="Minimum Amount" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Maximum Amount') }}</label>
                      <input type="number" step="any" class="form-control" name="max_amount" placeholder="Maximum Amount" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>{{ __('Percent Return') }}</label>
                  <input type="number" step="any" class="form-control" name="percent_return" placeholder="Percent Return">
                </div>

                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Duration') }}</label>
                        <input type="number"  class="form-control" name="duration" placeholder="Days" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Status') }}</label>
                      <select name="status" class="form-control">
                      
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Deactive') }}</option>
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
@endsection

