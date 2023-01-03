@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Customers'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                  
                  <form method="GET" action="{{ route('customer.index') }}">
                    <div class="form-row">
                      <div class = "col-lg-6">
                        <div class="input-group mb-2">
                          <input type="text" class="form-control" placeholder="Search..." required="" name="src" autocomplete="off" value="">
                          <select class="form-control" name="type" required="">
                              <option value="email">{{ __('Search By Email') }}</option>
                              <option value="phone">{{ __('Search By Phone') }}</option>
                              <option value="national_id">{{ __('Search By National ID') }}</option>
                          </select>
                        </div>
                      </div>

                    <div class="col-lg-6 ">
                        <div class="form-group "> 
                          <div class="input-group">                                            
                              <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>Search</button>
                              <a href="{{ route('customer.index') }}" class="btn btn-danger ml-2"><i class="fas fa-sync"></i>Refresh</a>
                          </div>
                        </div>
                    </div>
                    </div>
                  </form>
                  
                </div>
                @can('customer.create')
                <div class="col-lg-6">
                    <div class="add-new-btn">
                        <a href="{{ route('customer.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus"></i>{{ __('Add New Customer') }}</a>
                    </div>
                </div>
                @endcan
            </div>
            <div class="table-responsive">
            <table class="table table-striped" id="customertable-2">
                  <thead>
                    <tr>
                      <!-- <th>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                          <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                        </div>
                      </th> -->
                      <th> # </th>
                      <th>{{ __('Name') }}</th>
                      <th>{{ __('Email') }}</th>
                      <th>{{ __('Phone') }}</th>
                      <th>{{ __('National Id') }}</th>
                      <th>{{ __('Guarantor') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($customers as $key => $customer)
                    <tr>
                      <!-- <td>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1">
                          <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                        </div>
                      </td> -->
                      <td>{{ $key+1 }}</td>
                      <td>{{ $customer->first_name }}{{ $customer->first_name }}</td>
                      <td>
                        {{ $customer->email }}
                      </td>
                      <td>
                        {{ $customer->phone }}
                      </td>
                      <td>{{ $customer->national_id }}</td>
                      <td>{{ $customer->guarantor_first_name }}{{ $customer->guarantor_last_name }}</td>
                      <td>
                        @if($customer->status == 'active')
                        <span class="badge badge-success">{{ $customer->status }}</span>
                        @else
                        <span class="badge badge-danger">{{ $customer->status }}</span>
                        @endif
                      </td>
                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <div class="dropdown-menu">
                            @can('customer.view')
                            <a class="dropdown-item has-icon" href="{{ route('customer.show', $customer->id) }}"><i class="fa fa-eye"></i>{{ __('View') }}</a>
                            @endcan
                            @can('customer.edit')
                            <a class="dropdown-item has-icon" href="{{ route('customer.edit', $customer->id) }}"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>
                            @endcan
                           @can('customer.delete')
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $customer->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                            <!-- Delete Form -->
                            <form class="d-none" id="delete_form_{{ $customer->id }}" action="{{ route('customer.destroy', $customer->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            </form>
                            @endcan
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>


              </div>
               {{ $customers->links('vendor.pagination.bootstrap-4') }}
          </div>
      </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#customertable-2').DataTable({
            responsive: true,
            "order": [[ 0, "desc" ]],
        });
    });
</script>
@endpush