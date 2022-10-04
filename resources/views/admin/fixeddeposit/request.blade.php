@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'In Queue'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                    <h4>{{ __('Fixed Deposit Queue Lists') }}</h4>
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
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($deposit_request as $row)
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
                      @if($row->status == 2)
                      <td><span class="badge badge-primary">{{ __('Waiting') }}</span></td>
                      @endif
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.fixed.deposit.approved', $row->id) }}"><i class="fa fa-hammer"></i>{{ __('Complete Now') }}</a>
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $row->id }}><i class="fa fa-trash"></i>{{ __('Rejected') }}</a>
                            <!-- Delete Form -->
                            <form class="d-none" id="delete_form_{{ $row->id }}" action="{{ route('admin.fixed.deposit.rejected', $row->id) }}" method="POST">
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