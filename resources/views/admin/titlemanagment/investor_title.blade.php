@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Top Invetor Title Update') }}</h4>
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
            <form method="POST" action="{{ route('admin.top.investor.title.update', $investor_title->id) }}" class="basicform">
              @csrf
              @method('put')
              @php
                  $info = json_decode($investor_title->value);
              @endphp
              <div class="card-body">
                <div class="form-group">
                    <label>{{ __('Title') }}</label>
                    <input type="text" class="form-control" placeholder="Title" required name="title" value="{{ $info->title }}">
                </div>
                <div class="form-group">
                    <label>{{ __('Description') }}</label>
                    <textarea name="sub_title" class="form-control">
                        {{ $info->sub_title }}
                    </textarea>
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