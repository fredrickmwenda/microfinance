@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Investors List'])
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
                        <a href="{{ route('admin.investor.create') }}" class="btn btn-primary float-right">{{ __('Add New Investor') }}</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                     
                      <th>{{ __('Title') }}</th>
                      <th>{{ __('Position') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Created At') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($investors as $row)
                    @php
                        $info_meta = json_decode($row->investor->value);
                    @endphp
                    <tr>
                     
                      <td>{{ $row->title }}</td>
                      <td>{{ $info_meta->position }}</td>
                      @if($row->status == 1)
                      <td ><span class="badge badge-success">{{ __('Active') }}</span></td>
                      @endif
                      @if($row->status == 0)
                      <td><span class="badge badge-danger">{{ __('Inactive') }}</span></td>
                      @endif
                      <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.investor.edit', $row->id) }}"><i class="fa fa-edit"></i>{{ __('edit') }}</a>
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $row->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                            <!-- Delete Form -->
                            <form class="d-none" id="delete_form_{{ $row->id }}" action="{{ route('admin.investor.destroy', $row->id) }}" method="POST">
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
                {{ $investors->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
      </div>
  </div>
</div>
@endsection

