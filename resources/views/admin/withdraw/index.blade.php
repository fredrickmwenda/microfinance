@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'withdraw List'])
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
                        <a href="{{ route('admin.withdraw.create') }}" class="btn btn-primary float-right">{{ __('Add New withdraw') }}</a>
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
                      <th>{{ __('Maximum Limit') }}</th>
                      <th>{{ __('Created At') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($withdraw_lists as $row)
                    <tr>
                      <td>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1">
                          <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                        </div>
                      </td>
                      <td>{{ $row->title }}</td>
                      <td>
                        {{ $row->max_amount }}
                      </td>
                      <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item has-icon" href="{{ route('admin.withdraw.edit', $row->id) }}"><i class="fa fa-edit"></i>{{ __('edit') }}</a>
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