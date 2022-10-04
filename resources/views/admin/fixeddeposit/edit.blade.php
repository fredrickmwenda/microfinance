@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Fixed Deposit Edit') }}</h4>
            </div>
            <form method="POST" action="{{ route('admin.fixed-deposit.update', $fdr_plan->id) }}" class="basicform">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label>{{ __('Title') }}</label>
                  <input type="text" class="form-control" name="title" value="{{ $fdr_plan->title }}" placeholder="Name" required>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Minimum Amount') }}</label>
                        <input type="number" step="any" value="{{ $fdr_plan->min_amount }}" class="form-control" name="min_amount" placeholder="Minimum Amount" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Maximum Amount') }}</label>
                      <input type="number" step="any" value="{{ $fdr_plan->max_amount }}" class="form-control" name="max_amount" placeholder="Maximum Amount" required>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>{{ __('Percent Return') }}</label>
                  <input type="number" step="any" value="{{ $fdr_plan->percent_return }}" class="form-control" name="percent_return" placeholder="Percent Return">
                </div>

                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Duration') }}</label>
                        <input type="number"  value="{{ $fdr_plan->duration }}" class="form-control" name="duration" placeholder="Days" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Status') }}</label>
                      <select name="status" class="form-control">
                        
                        <option value="1" {{ $fdr_plan->status == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="0" {{ $fdr_plan->status == '0' ? 'selected' : '' }}>{{ __('Deactive') }}</option>
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

