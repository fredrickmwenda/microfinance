@extends('layouts.backend.app')

@push('css')
  <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Add New Page') }}</h4>
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
            <form method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data" class="basicform_with_reset">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label>{{ __('Page Title') }}</label>
                  <input type="text" class="form-control" placeholder="Page Title" required name="page_title">
                </div>

                <div class="form-group">
                    <label>{{ __('Page excerpt') }}</label>
                    <textarea name="page_excerpt" cols="30" rows="10" class="form-control"></textarea>
                </div>
          
                <div class="form-group">
                  <label>{{ __('Page Content') }}</label>
                  <textarea name="page_content" class="form-control summernote"></textarea>
                </div>

              <div class="form-group">
                <div class="custom-file mb-3">
                  <label>{{ __('Status') }}</label>
                  <select name="status" class="form-control">
                  
                    <option value="1">{{ __('Active') }}</option>
                    <option value="0">{{ __('Inactive') }}</option>
                  </select>
                </div>
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
