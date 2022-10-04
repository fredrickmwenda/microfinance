@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{ __('Currency Create') }}</h4>
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
            <form method="POST" action="{{ route('admin.currency.store') }}" class="basicform_with_reset">
              @csrf
              <div class="card-body">              
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Country Name') }}</label>
                    <div class="col-sm-12 col-md-7">
                      <input type="text" class="form-control" placeholder="Title" required name="title">
                    </div>
                  </div>                  
                                   
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Rate') }}</label>
                    <div class="col-sm-12 col-md-7">
                      <input type="number" step="any" class="form-control" placeholder="Rate" name="slug">
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                    <div class="col-sm-12 col-md-7">
                      <select name="status" class="form-control">
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('In-Active') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Logo') }}</label>
                    <div class="col-sm-12 col-md-7">
                      <input type="file" class="form-control" name="logo">
                    </div>
                  </div> 
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                    <div class="col-sm-12 col-md-7">
                      <button type="submit" class="btn btn-primary btn-lg  basicbtn">{{ __('Submit') }}</button>
                    </div>
                  </div>
              </div>
          </form>
        </div>
    </div>
</div>
@endsection



