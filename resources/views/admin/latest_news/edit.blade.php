@extends('layouts.backend.app')

@push('css')
  <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Edit News') }}</h4>
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
            <form method="POST" action="{{ route('admin.latest_news.update', $ln_edit->id) }}" enctype="multipart/form-data" class="basicform">
              @csrf
              @method('put')
              @php
               $excerpt_info = $ln_edit->excerpt->value;
               $description_info = $ln_edit->description->value;
               $thumimg_info = $ln_edit->thum_image->value;
              @endphp
              <div class="card-body">
                <div class="form-group">
                  <label>{{ __('Name') }}</label>
                  <input type="text" class="form-control" placeholder="Name" required name="name" value="{{ $ln_edit->title }}">
                </div>

                <div class="form-group">
                    <label>{{ __('Short Content') }}</label>
                    <textarea name="excerpt" cols="30" rows="10" class="form-control">{{ $excerpt_info }}</textarea>
                </div>
          
                <div class="form-group">
                  <label>{{ __('Description') }}</label>
                  <textarea name="description" class="form-control summernote">{{ $description_info }}</textarea>
                </div>

              <div class="form-group">
                <div class="custom-file mb-3">
                  <input type="file" class="custom-file-input" id="customFile" name="thum_image">
                  <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                </div>
                <br>
                <img width="100" src="{{ asset($thumimg_info) }}" alt="{{ $thumimg_info }}">
              </div>
              <div class="form-group">
                <div class="custom-file mb-3">
                  <label>{{ __('Status') }}</label>
                  <select name="status" class="form-control">
                   
                    <option value="1" {{ ($ln_edit->status == 1) ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="0" {{ ($ln_edit->status == 0) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
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

@push('js')
  <script src="{{ asset('backend/admin/assets/js/summernote-bs4.js') }}"></script>
  <script>
    $('.summernote').summernote({
       height: 300,                 // set editor height
       minHeight: null,             // set minimum height of editor
       maxHeight: null,             // set maximum height of editor
       focus: true                  // set focus to editable area after initializing summernote
     });
  </script>
@endpush
