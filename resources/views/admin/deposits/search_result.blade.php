@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'All Deposit List'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                    <form method="POST" action="{{ route('admin.deposit.search') }}">
                      @csrf
                      <div class="input-group mb-2 col-12">
                         <input type="text" class="form-control" placeholder="Search..." required="" name="q" autocomplete="off" value="">
                         <select class="form-control" name="type">
                            <option value="user_name">{{ __('Search By User') }}</option>
                            <option value="trx">{{ __('Search By Trx') }}</option>
                         </select>
                         <div class="input-group-append">                                            
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                         </div>
                      </div>
                   </form>
                  </div>
                <div class="col-lg-6">
                    <div class="add-new-btn">
                        <a href="{{ route('admin.deposit.create') }}" class="btn btn-primary float-right">{{ __('Add New Deposit') }}</a>
                    </div>
                </div>
                <div class="col-lg-12">
                  @if(Session::has('message'))
                    <p class="alert alert-danger">
                        {{ Session::get('message') }}
                    </p>
                  @endif
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
                      <th>{{ __('Trx') }}</th>
                      <th>{{ __('User') }}</th>
                      <th>{{ __('Payment Gateway') }}</th>
                      <th>{{ __('Amount') }}</th>
                      <th>{{ __('Charge') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($deposits as $deposit)
                    <tr>
                      <td>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1">
                          <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                        </div>
                      </td>
                      <td>{{ $deposit->trx }}</td>
                      <td class="align-middle"> {{ $deposit->user->name }}</td>
                      <td>{{ $deposit->getway->name }}</td>
                      <td>{{ $deposit->amount }}</td>
                      <td>{{ $deposit->charge }}</td>
                      <td>
                        @if ($deposit->status == 1)
                          {{ __('Success') }}
                        @elseif($deposit->status == 0)
                          {{ __('Cancelled') }}
                        @else 
                          {{ __('Pending') }}
                        @endif
                      </td>
                      <td>
                        @if ($deposit->status != 0)
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $deposit->id }}><i class="fa fa-trash"></i>{{ __('Cancel') }}</a>
                            <!-- Delete Form -->
                            <form class="d-none" id="delete_form_{{ $deposit->id }}" action="{{ route('admin.deposit.update', $deposit->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            </form>
                          </div>
                        </div>
                        @endif
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


