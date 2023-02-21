@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'All Disbursement List'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-3">
              <div class="col-lg-8">       
                <form method="GET" action="{{ route('admin.disburse.index') }}">
                  <div class="form-row">
                      <div class="col-lg-6">
                          <!--input transaction_no-->
                          <div class="input-group form-row">
                            <input type="text" class="form-control" placeholder="Search..." required="" name="value" autocomplete="off" value="" id="query_term">
                            <select class="form-control" name="type">               
                                <option value="trxid">{{ __('Transaction No') }}</option>
                                <option value="name">{{ __('Customer Name') }}</option>
                                <option value="national_id">{{ __('National ID') }}</option>
                            </select>
                          </div> 
                      </div>
                      <!--Filter and clear button-->
                      <div class="col-lg-6 ">
                        <div class="form-group ">              
                            <div class="input-group">
                              <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>Search</button>
                              <!--clear button have a refresh icon-->
                              <a href="{{ route('admin.disburse.index') }}" class="btn btn-danger ml-2"><i class="fas fa-sync-alt"></i>Clear</a>
                            </div>
                         
                        </div>
                      </div>                                    
                  </div>
                </form>
              </div>
              <div class="col-lg-4">
                @can('disburse.create')
                <div class="float-right">
                  <a href="{{ route('admin.disburse.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('Add New Disbursement') }}</a>
                </div>
                @endcan
              </div>

            </div>
            @if (Session::has('message'))
              <div class="alert alert-danger">{{ Session::get('message') }}</div>
            @endif
            @isset($start_date)
            <strong>{{ date('d-m-Y', strtotime($start_date)) }} {{ __('Date To') }} {{ date('d-m-Y', strtotime($end_date)) }} {{ __('Date Report') }}</strong>
            <br>
            @endisset
            <div class="table-responsive">
                <table class="table table-striped" id="disburse_list">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Transaction No') }}</th>
                        <th>{{ __('Customer Name') }}</th>
                        <th>{{ __('Customer Phone') }}</th>
                        <th>{{ __('Amount Disbursed') }}</th>
                        <th>{{ __('Disburser') }}</th>
                        <th>{{ __('Date Disbursed') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($disbursements as $key => $row)
                    <tr>
                      <td>{{ $key+1 }}</td>
                      <td>{{ $row->disbursement_code }}</td>
                      <td>
                        <a href="">{{ $row->disbursedTo->first_name }} {{ $row->disbursedTo->last_name }}</a>
                      </td>
                      <td>{{ $row->disbursedTo->phone }}</td>
                      <td>
                        {{ $row->disbursement_amount }}
                        
                      </td>

                      <td>
                        {{ $row->disburser->first_name }} {{ $row->disburser->last_name }}
                      </td>
                      <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                      <td>
                          @if($row->status == "success")
                          <span class="badge badge-success">{{ __('Success') }}</span>
                          @else
                          <span class="badge badge-danger">{{ __('Failed') }}</span>
                          @endif
                      </td>
                      <td>
                        <div class="dropdown">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <!--edit and delete-->
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <!-- @can('disburse.edit')
                            <a class="dropdown-item" href="{{ route('admin.disburse.edit', $row->id) }}"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>
                            @endcan -->
                            @can('disburse.delete')
                            <a class="dropdown-item" href="javascript:void(0);" data-id="{{ $row->id }}" ><i class="fa fa-trash"></i> {{ __('Delete') }}</a>
                            <!--delete-->
                            <form id="delete-form-{{ $row->id }}" action="{{ route('admin.disburse.destroy', $row->id) }}" method="POST" style="display: none;">
                              @csrf
                              @method('DELETE')
                            </form>
                            @endcan
                          </div>

                        </div>  
                        <!-- <a title="view" class="btn btn-info btn-sm" href="{{ route('admin.disburse.show', $row->id) }}">
                            <i class="fa fa-eye"></i>
                        </a> -->
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

@push ('js')
<!-- <script src="{{ asset('assets/backend/js/datatables.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/code.js') }}"></script> -->
<!--make the table a responsive data table-->
<script>
    $(document).ready(function() {
        $('#disburse_list').DataTable({
            responsive: true,
            "order": [[ 0, "desc" ]],


        });
    });
</script>
@endpush        