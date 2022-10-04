@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{__('Home Page Hero Section Edit')}}</h4>
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
            <form method="POST" action="{{ route('admin.option.hero.section.update', $hero_data->id) }}" class="basicform_with_reload">
              @php
                  $info = json_decode($hero_data->value);  
              @endphp
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                    <label>{{ __('Title') }}</label>
                    <input type="text" class="form-control" value="{{ $info->title }}" name="title" placeholder="Title" required>
                </div>
                <div class="form-group">
                    <label>{{ __('Short Description') }}</label>
                    <textarea class="form-control" name="short_description" id="" cols="30" rows="10">{{ $info->short_description }}</textarea>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Button Text') }}</label>
                        <input type="text" class="form-control" value="{{ $info->button_text }}" name="button_text" placeholder="Button Text">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Video Url') }}</label>
                      <input type="text" class="form-control" value="{{ $info->button_url }}"  name="button_url" placeholder="Video Url">
                    </div>
                  </div>
                </div> 
                <div class="form-group">
                    <label>{{ __('Status') }}</label>
                    <select name="status" class="form-control">
                        <option {{ ($info->status == 1) ? 'selected' : '' }} value="1">{{ __('Active') }}</option>
                        <option {{ ($info->status == 0) ? 'selected' : '' }} value="0">{{ __('Inactive') }}</option>
                    </select>
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