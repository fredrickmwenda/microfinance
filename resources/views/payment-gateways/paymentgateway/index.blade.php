@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Manage Payment Gateway'])
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
                        <a href="{{ route('admin.payment.gateway.create') }}" class="btn btn-primary float-right">{{ __('Add New Gateway') }}</a>
                    </div>
                </div>
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
                    @foreach($payments_gateway as $row)
                    @php
                      $info = json_decode($row->paymentMeta->value);
                    @endphp
                    <tr>
                      
                      <td>{{ $row->title }}</td>
                      <td>
                        <img width="80" src="{{ asset($info->logo) }}" alt="{{ $info->logo }}">
                      <td>
                        {{ $info->max_amount }}
                      </td>
                      <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Action
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.payment.gateway.edit', $row->id) }}"><i class="fa fa-edit"></i>{{ __('edit') }}</a>
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


