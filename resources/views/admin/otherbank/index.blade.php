@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Manage Other Banks'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                    <h4>{{ __('Others Bank List') }}</h4>
                </div>
                <div class="col-lg-6">
                    <div class="add-new-btn">
                        <a href="{{ route('admin.others-bank.create') }}" class="btn btn-primary float-right">{{ __('Add New Bank') }}</a>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
              <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                      <th>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                          <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                        </div>
                      </th>
                      <th>{{ __('Bank Name') }}</th>
                      <th>{{ __('Country') }}</th>
                      <th>{{ __('Maximum Limit') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Created At') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($banks as $bank)
                    <tr>
                      <td>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1">
                          <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                        </div>
                      </td>
                      <td>{{ $bank->name }}</td>
                      <td>{{ $bank->country->title }}</td>
                      <td class="align-middle">
                        {{ $bank->max_amount }}
                      </td>
                      <td>
                        {{ $bank->status == 0 ? 'Inactive' : 'Active' }}
                      </td>
                      <td>{{ $bank->created_at }}</td>
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.others-bank.show', $bank->id) }}"><i class="fa fa-eye"></i>{{ __('View') }}</a>
                            <a class="dropdown-item has-icon" href="{{ route('admin.others-bank.edit', $bank->id) }}"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>

                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $bank->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>

                            <form class="d-none" id="delete_form_{{ $bank->id }}" action="{{ route('admin.others-bank.destroy', $bank->id) }}" method="POST">
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
              
                {{ $banks->links('vendor.pagination.bootstrap-4') }}
            </div>
          </div>
      </div>
    </div>
</div>
@endsection

@push('js')
  <script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
@endpush


