@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{ __('Country Edit') }}</h4>
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
            <form method="POST" action="{{ route('admin.country.update', $country->id) }}" class="basicform">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Country Name') }}</label>
                  <div class="col-sm-12 col-md-7">
                    <input type="text" value="{{ $country->title }}" class="form-control" placeholder="Country Name" required name="title">
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                  <div class="col-sm-12 col-md-7">
                    <select name="status" class="form-control" required>
                      <option value="1" {{ $country->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                      <option value="0" {{ $country->status == 0 ? 'selected' : '' }}>{{ __('In-Active') }}</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                  <div class="col-sm-12 col-md-7">
                    <button type="submit" class="btn btn-primary btn-lg  basicbtn">{{ __('Update') }}</button>
                  </div>
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection



