@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Edit Investor') }}</h4>
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
            @php
                $info_edit = json_decode($investor_edit->investor->value);
            @endphp
            <form method="POST" action="{{ route('admin.investor.update', $investor_edit->id) }}" enctype="multipart/form-data" class="basicform">
              @csrf
              @method('put')
              <div class="card-body">
                <div class="form-group">
                  <label>{{ __('Name') }}</label>
                  <input type="text" class="form-control" placeholder="Name" required name="name" value="{{ $investor_edit->title }}">
                </div> 
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                          <label>{{ __('Position') }}</label>
                          <input type="text" class="form-control" placeholder="Position" required name="position" value="{{ $info_edit->position }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Facebook Link') }}</label>
                        <input type="text" class="form-control" placeholder="Facebook Link" required name="facebook_link" value="{{ $info_edit->facebook_link }}">
                      </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                          <label>{{ __('Twitter Link') }}</label>
                          <input type="text" class="form-control" placeholder="Twitter Link" required name="twitter_link" value="{{ $info_edit->twitter_link }}">
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Linkedin Link') }}</label>
                        <input type="text" class="form-control" placeholder="Linkedin Link" required name="linkedin_link" value="{{ $info_edit->linkedin_link }}">
                      </div>
                    </div>
                </div>
              <div class="form-group">
                <div class="custom-file mb-3">
                  <input type="file" class="custom-file-input" id="customFile" name="image">
                  <label class="custom-file-label" for="customFile">{{ __('Image (Choose file)') }}</label>
                </div>
                <br>
                <img width="50" src="{{ asset($info_edit->image) }}" alt="{{ $info_edit->image }}">
              </div>
              <div class="form-group">
                <div class="custom-file mb-3">
                  <label>{{ __('Status') }}</label>
                  <select name="status" class="form-control">
                   
                    <option value="1" {{ ($investor_edit->status == 1) ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="0" {{ ($investor_edit->status == 0) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                  </select>
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
@endsection