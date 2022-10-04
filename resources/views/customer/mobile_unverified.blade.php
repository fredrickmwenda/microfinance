@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'mobile unverified user'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                  <form method="POST" action="{{ route('admin.user.search') }}">
                    @csrf
                    <div class="input-group mb-2 col-12">
                       <input type="text" class="form-control" placeholder="Search..." required="" name="src" autocomplete="off" value="">
                       <select class="form-control" name="type">
                          <option value="email">{{ __('Search By Email') }}</option>
                          <option value="phone">{{ __('Search By Phone') }}</option>
                          <option value="account_number">{{ __('Search By Account Number') }}</option>
                       </select>
                       <div class="input-group-append">                                            
                          <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                       </div>
                    </div>
                  </form>
                </div>
                <div class="col-lg-6">
                    <div class="add-new-btn">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary float-right">{{ __('Add New user') }}</a>
                    </div>
                </div>
            </div>
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
                      <th>{{ __('Name') }}</th>
                      <th>{{ __('Email') }}</th>
                      <th>{{ __('Phone') }}</th>
                      <th>{{ __('Balance') }}</th>
                      <th>{{ __('Account Number') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($mobile_unverified as $row)
                    <tr>
                      <td>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1">
                          <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                        </div>
                      </td>
                      <td>{{ $row->name }}</td>
                      <td>
                        {{ $row->email }}
                      </td>
                      <td>
                        {{ $row->phone }}
                      </td>
                      <td>{{ $row->balance }}</td>
                      <td>{{ $row->account_number }}</td>
                      @if($row->status == 1)
                      <td class="text-success">{{ __('Active') }}</td>
                      @endif
                      @if($row->status == 0)
                      <td class="text-danger">{{ __('Inactive') }}</td>
                      @endif
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.users.edit', $row->id) }}"><i class="fa fa-edit"></i>{{ __('edit') }}</a>
                            <a class="dropdown-item has-icon" href="{{ url('/login') }}"><i class="fa fa-edit"></i>{{ __('Login') }}</a>
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $row->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                            <!-- Delete Form -->
                            <form class="d-none" id="delete_form_{{ $row->id }}" action="{{ route('admin.users.destroy', $row->id) }}" method="POST">
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
            </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
@endpush