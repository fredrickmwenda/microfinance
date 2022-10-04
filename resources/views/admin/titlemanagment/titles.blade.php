@extends('layouts.backend.app')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
           
            @if ($errors->any())
              <div class="alert alert-danger">
                  <strong>Whoops!</strong> There were some problems with your input.<br><br>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <form method="POST" action="{{ route('admin.client.freedback.title.update') }}" class="basicform">
              @csrf
              @method('put')
               <div class="card-header">
                <h4>{{ __('Hero Area Titles') }}</h4>
               </div>
              <div class="card-body">
                <div class="form-group">
                    <label>{{ __('Title') }}</label>
                    <input type="text" class="form-control" placeholder="Title" required name="hero_title" value="{{ $data->hero_title ?? '' }}">
                </div>
                <div class="form-group">
                    <label>{{ __('Button text') }}</label>
                    <input type="text" class="form-control" placeholder="Button text" required name="hero_btn_title" value="{{ $data->hero_btn_title ?? '' }}">
                </div>
                <div class="form-group">
                    <label>{{ __('Video Url') }}</label>
                    <input type="text" class="form-control" placeholder="Youtube Video Url" required name="hero_button_url" value="{{ $data->hero_button_url ?? '' }}">
                </div>
                
                <div class="form-group">
                    <label>{{ __('Description') }}</label>
                    <textarea name="hero_description" class="form-control">{{ $data->hero_description ?? '' }}</textarea>
                </div>               
              </div>


               <div class="card-header">
                <h4>{{ __('Client Feedback Title Update') }}</h4>
               </div>
              <div class="card-body">
                <div class="form-group">
                    <label>{{ __('Title') }}</label>
                    <input type="text" class="form-control" placeholder="Title" required name="client_title" value="{{ $data->client_title ?? '' }}">
                </div>
                <div class="form-group">
                    <label>{{ __('Description') }}</label>
                    <textarea name="client_description" class="form-control">{{ $data->client_description ?? '' }}</textarea>
                </div>               
              </div>

            <div class="card-header">
            <h4>{{ __('How it works Title Update') }}</h4>
            </div>
              <div class="card-body">
                <div class="form-group">
                  <label>{{ __('Title') }}</label>
                  <input type="text" class="form-control" placeholder="Title" required name="hwt_title" value="{{ $data->hwt_title ?? '' }}">
                </div>
                <div class="form-group">
                  <label>{{ __('Description') }}</label>
                  <textarea name="hwt_description" class="form-control">{{ $data->hwt_description ?? '' }}</textarea>
                </div>               
              </div>


              <div class="card-header">
                <h4>{{ __('Top Investor Title Update') }}</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>{{ __('Title') }}</label>
                  <input type="text" class="form-control" placeholder="Title" value="{{ $data->tit_title ?? '' }}" required name="tit_title" >
                </div>
                <div class="form-group">
                  <label>{{ __('Description') }}</label>
                  <textarea name="tit_description" class="form-control">{{ $data->tit_description ?? '' }}</textarea>
                </div>               
              </div>

              <div class="card-header">
                <h4>{{ __('Latest News Title Update') }}</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>{{ __('Title') }}</label>
                  <input type="text" class="form-control" value="{{ $data->lnt_title ?? '' }}" placeholder="Title" required name="lnt_title" >
                </div>
                <div class="form-group">
                  <label>{{ __('Description') }}</label>
                  <textarea name="lnt_description" class="form-control">{{ $data->lnt_description ?? '' }}</textarea>
                </div>               
              </div>

              <div class="card-header">
                <h4>{{ __('Banking Services Title Update') }}</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>{{ __('Title') }}</label>
                  <input type="text" class="form-control" value="{{ $data->bst_title ?? '' }}" placeholder="Title" required name="bst_title" >
                </div>
                <div class="form-group">
                  <label>{{ __('Description') }}</label>
                  <textarea name="bst_description" class="form-control">{{ $data->bst_description ?? '' }}</textarea>
                </div>               
              </div>


               <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn">{{ __('Update') }}</button>
                  </div>
                </div>
            </form>
          </div>
    </div>
  </div>

@endsection