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
                  <form method="POST" action="{{ route('customer.search') }}">
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
                        <a href="{{ route('customer.create') }}" class="btn btn-primary float-right">{{ __('Add New Customer') }}</a>
                    </div>
                </div>
            </div>
            <div class="">
                <table class="table table-responsive table-striped" id="customertable-2">
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
                      <th>{{ __('National Id') }}</th>
                      <th>{{ __('Guarantor') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($customers as $row)
                    <tr>
                      <td>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1">
                          <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                        </div>
                      </td>
                      <td>{{ $row->first_name }}{{ $row->first_name }}</td>
                      <td>
                        {{ $row->email }}
                      </td>
                      <td>
                        {{ $row->phone }}
                      </td>
                      <td>{{ $row->national_id }}</td>
                      <td>{{ $row->guarantor_first_name }}{{ $row->guarantor_last_name }}</td>
                      
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
                            <a class="dropdown-item has-icon" href="{{ route('customer.show', $row->id) }}"><i class="fa fa-eye"></i>{{ __('View') }}</a>
                            <a class="dropdown-item has-icon" href="{{ route('customer.edit', $row->id) }}"><i class="fa fa-edit"></i>{{ __('edit') }}</a>
  
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $row->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                            <!-- Delete Form -->
                            <form class="d-none" id="delete_form_{{ $row->id }}" action="{{ route('customer.destroy', $row->id) }}" method="POST">
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
               {{ $customers->links('vendor.pagination.bootstrap-4') }}
          </div>
      </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#customertable-2').DataTable({
            responsive: true,
            "order": [[ 0, "desc" ]],
        });
    });
</script>
@endpush