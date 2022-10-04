@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Automatic Gateway List'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                     <th>{{ __('Name') }}</th>
                      <th>{{ __('Logo') }}</th>
                      <th>{{ __('Maximum Limit') }}</th>
                      <th>{{ __('Created At') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($automaticGateways as $automaticGateway)   
                      <tr>
                      
                      <td>{{ $automaticGateway->name }}</td>
                      <td class="align-middle"> 
                          <img width="80" src="{{ asset($automaticGateway->logo) }}" alt="{{ $automaticGateway->logo }}">
                      </td>
                      <td>{{ $automaticGateway->deposit_max }}</td>
                      <td>{{ date('d-m-Y', strtotime($automaticGateway->created_at)) }}</td>
                      <td>
                        @if (Auth()->user()->can('deposit.automatic.gateway.edit')) 
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>     
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.deposit.automatic.gateway.edit', $automaticGateway->id) }}"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>
                          </div>
                        </div>
                        @endif
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="float-right">
                  {{ $automaticGateways->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
@endpush
