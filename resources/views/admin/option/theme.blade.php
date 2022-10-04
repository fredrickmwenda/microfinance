@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{__('')}}</h4>
            </div>
            @if (Session::has('message'))
                <div class="alert alert-danger">
                </div>
            @endif
            <form method="POST" action="{{ route('admin.theme.settings.update', $theme->id) }}" enctype="multipart/form-data" class="basicform">
              @csrf
              @method('PUT')
              @php 
                $theme = json_decode($theme->value) ?? '';
              @endphp
              <div class="card-body">
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Logo') }}</label>
                        <input type="file" class="form-control" name="logo" id="">
                        {{ __('Prev photo') }}: <img class="mt-2" src="{{ asset('uploads/logo.png') }}" alt="" width="100">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Favicon') }}</label>
                        <input type="file" class="form-control" name="favicon" id="">
                        {{ __('Prev photo') }}: <img class="mt-2" src="{{ asset('uploads/favicon.ico') }}" alt="" width="100">
                    </div>
                  </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-12 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Footer Description') }}</label>
                            <textarea name="footer_description" class="form-control">{{ $theme->footer_description ?? '' }}</textarea>
                        </div>
                      </div>
                      <div class="col-lg-12 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Address') }}</label>
                            <textarea name="newsletter_address" class="form-control">{{ $theme->newsletter_address ?? '' }}</textarea>
                        </div>
                      </div>
                    </div>
                    <div class="form-group field_wrapper">
                        <div class="row">
                            <div class="col-md-5"> 
                                <label for="">{{ __('Iconify Icon') }}</label> <br>
                            </div>
                            <div class="col-md-5">
                                <label for="">{{ __('Link') }}</label><br>
                            </div>
                            <div class="col-md-2">
                                <a href="javascript:void(0);" class="add_button btn btn-outline-primary btn-block" title="Add field">Add <i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        @foreach ($theme->social ?? [] as $key => $item)
                        <div class="row">
                            <div class="col-md-5"><br>
                                <input type="text" value="{{ $item->icon }}" data-key="{{ $key }}" class="form-control" name="social[{{ $key }}][icon]" value='fab fa-facebook'> 
                            </div>
                            <div class="col-md-5"><br>
                                <input type="text" value="{{ $item->link }}" class="form-control" name="social[{{ $key }}][link]" class=""> 
                            </div>
                            <div class="col-md-2">
                                <a href="javascript:void(0);" class="remove_button btn btn-danger btn-block" title="Add field">Remove <i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        @endforeach
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

@push('js')
<script>
    'use strict';

    $(document).ready(function(){
        var x = 0; //Initial field counter is 1
        var count = 100;
        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
    
        //Once add button is clicked
        $(addButton).on('click',function(){
            //Check maximum number of input fields
            if(x < maxField){ 
                 //Increment field counter
                var fieldHTML = `<div class='row'><div class="col-md-5"> 
                                <br>
                                <input type="text" required class="form-control" name="social[${count}][icon]" value=""> 
                                </div>
                                <div class="col-md-5">
                                    <br>
                                    <input type="text" required class="form-control" name="social[${count}][link]" class=""> 
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:void(0);" class="remove_button btn btn-danger btn-block" title="Add field">Remove <i class="fas fa-minus"></i></a>
                                </div><div>`; //New input field html 
                x++;
                count++;
                $(wrapper).append(fieldHTML); //Add field html
            }
        });

        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent('div').parent('div.row').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });
</script>
@endpush




