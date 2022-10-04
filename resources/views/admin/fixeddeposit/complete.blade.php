@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Completed FDR'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                    <h4>{{ __('Fixed Deposit Completed Lists') }}</h4>
                </div>
                <div class="col-lg-6">
                    
                </div>
            </div>
            @if (Session::has('success'))
              <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            @if (Session::has('error'))
              <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                      <th>{{ __('User Name') }}</th>
                      <th>{{ __('Package Name') }}</th>
                      <th>{{ __('Total Amount') }}</th>
                      <th>{{ __('Total Return') }}</th>
                      <th>{{ __('Return Date') }}</th>
                      <th>{{ __('Status') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($deposit_complete as $row)
                    <tr>
                      <td><a href="{{ url('/admin/users',$row->user->id) }}">{{ $row->user->name }}</a></td>
                      <td class="align-middle">
                        {{ $row->fdrplan->title }}
                      </td>
                      <td>
                        {{ $row->amount }}
                      </td>
                      <td>{{ $row->return_total }}</td>
                      <td>{{ date('d-m-Y', strtotime($row->return_date)) }}</td>
                      <td><span class="badge badge-success">{{ __('Complete') }}</span></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $deposit_complete->links('vendor.pagination.bootstrap-4') }}
              </div>
          </div>
      </div>
  </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
@endpush