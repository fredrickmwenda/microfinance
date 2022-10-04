@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Currency List'])
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
                        <a href="{{ route('admin.currency.create') }}" class="btn btn-primary float-right">{{ __('Add New Currency') }}</a>
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
                      <th>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                          <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                        </div>
                      </th>
                      <th>{{ __('Title') }}</th>
                      <th>{{ __('Image') }}</th>
                      <th>{{ __('Rate') }}</th>
                      <th>{{ __('Created At') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($currencies as $currency)
                    @php
                      $info = json_decode($currency->currencyMeta->value);
                    @endphp
                    <tr>
                      <td>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1">
                          <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                        </div>
                      </td>
                      <td>{{ $currency->title }}</td>
                      <td><img width="80" src="{{ asset($info->logo) }}" alt="{{ $info->logo }}"></td>
                      <td>{{ $currency->slug }}</td>
                      <td>{{ date('d-m-Y', strtotime($currency->created_at)) }}</td>
                      <td>
                        {{ $currency->status == 0 ? 'Inactive' : 'Active' }}
                      </td>
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="#"><i class="fa fa-eye"></i>{{ __('View') }}</a>
                            <a class="dropdown-item has-icon" href="{{ route('admin.currency.edit', $currency->id) }}"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $currency->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                            <!-- Delete Form -->
                            <form class="d-none" id="delete_form_{{ $currency->id }}" action="{{ route('admin.currency.destroy', $currency->id) }}" method="POST">
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
                 {{ $currencies->links('vendor.pagination.bootstrap-4') }}
            </div>
          </div>
      </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
@endpush


