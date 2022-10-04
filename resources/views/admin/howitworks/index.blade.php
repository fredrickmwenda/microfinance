@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Manage How it works'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                </div>
                <div class="col-lg-6">
                    <div class="add-new-btn">
                        <a href="{{ route('admin.howitworks.create') }}" class="btn btn-primary float-right">{{ __('Add New') }}</a>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
              <div class="alert alert-danger">{{ Session::get('message') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                     
                      <th>{{ __('Title') }}</th>
                      <th>{{ __('Image') }}</th>
                      <th>{{ __('Created At') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($howitworks as $howitwork)
                    @php
                    $info = json_decode($howitwork->howitworksMeta->value);
                    @endphp
                    <tr>
                     
                      <td>{{ $howitwork->title }}</td>
                      <td><img width="80" src="{{ asset($info->image) }}" alt="{{ $info->image }}">
                      </td>
                      <td>{{ date('d-m-Y', strtotime($howitwork->created_at)) }}</td>
                      <td>
                        {{ $howitwork->status == 0 ? 'Inactive' : 'Active' }}
                      </td>
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.howitworks.edit', $howitwork->id) }}"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $howitwork->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                            <!-- Delete Form -->
                            <form class="d-none" id="delete_form_{{ $howitwork->id }}" action="{{ route('admin.howitworks.destroy', $howitwork->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            </form>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                 {{ $howitworks->links('vendor.pagination.bootstrap-4') }}
              </div>
          </div>
      </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
@endpush


