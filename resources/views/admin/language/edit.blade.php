@extends('layouts.backend.app')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>{{ __('Customize Language') }}</h1>
    </div>
    <div class="section-body">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Customize Language') }}</h6>
            </div>
            <div class="card-body">
                <div class="table_append">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('Key') }}</th>
                            <th scope="col">{{ __('Value') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($langs as $key=>$lang)
                            <tr>
                                <td>{{ $key }}</td>
                                <td> 
                                    {{ $lang }}
                                </td>
                                <td><a href="#" class="btn btn-info"  data-toggle="modal" data-target="#lang_model_{{ Str::slug($lang) }}">{{ __('Edit') }}</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@foreach($langs as $key=>$lang)
<div class="modal fade langmodel" id="lang_model_{{ Str::slug($lang) }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Value') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('admin.language.update',$id) }}" method="POST" class="langform basicform">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                <label for="message-text" class="col-form-label">{{ __('Value') }}:</label>
                <textarea class="form-control text-lg" name="value">{{ $lang }}</textarea>
                </div>
            </div>
            <input type="hidden" name="key" value="{{ $key }}">
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>
        </form>
      </div>
    </div>
  </div>
@endforeach
@endsection

@push('js')
<script>
    "use strict";
    function success(res){
        $('.langmodel').modal('hide');
        $('.table_append').load(' .table_append');
    }
</script>
@endpush