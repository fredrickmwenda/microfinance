@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Loan Package List'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                  <h4>{{ __('Loan Package List') }}</h4>
                </div>
                <div class="col-lg-6">
                    <div class="add-new-btn">
                        <a href="{{ route('admin.loan.create') }}" class="btn btn-primary float-right">{{ __('Add New Package') }}</a>
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
                      
                      <th>{{ __('Name') }}</th>
                      <th>{{ __('Minium Amount') }}</th>
                      <th>{{ __('Maximum Amount') }}</th>
                      <th>{{ __('Duration') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($loans as $loan)
                    <tr>
                      <td>{{ $loan->name }}</td>
                      <td>
                        {{ $loan->min_amount }}
                      </td>
                      <td>{{ $loan->max_amount }}</td>
                      <td>{{ $loan->duration }}</td>
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.loan.edit', $loan->id) }}"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $loan->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                            <!-- Delete Form -->
                            <form class="d-none" id="delete_form_{{ $loan->id }}" action="{{ route('admin.loan.destroy', $loan->id) }}" method="POST">
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
                {{ $loans->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
@endpush