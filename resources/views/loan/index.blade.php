@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Loan List'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                <form action="{{ route('loan.index') }}" method="GET">
                  <div class="form-row">                                              
                    <div class="col-lg-6">
                      <div class="input-group form-row">
                        <input type="text" class="form-control" placeholder="Search..." required="" name="value" autocomplete="off" value="" id="query_term">
                        <select class="form-control" name="type">               
                            <option value="name">{{ __('Customer Name') }}</option>
                            <option value="phone">{{ __('Customer Phone') }}</option>                    
                            <option value="national_id">{{ __('National ID') }}</option>
                            <!-- <option value="status">{{ __('Loan Status') }}</option> -->
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group row">
                      <div class="col-lg-12">
                        <div class="input-group">
                          <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>Search</button>
                          <a href="{{ route('loan.index') }}" class="btn btn-danger ml-2"><i class="fas fa-sync-alt"></i>Refresh</a>
                        </div>
                      </div>                        
                      </div>
                    </div>
                  </div>
                </form>
                </div>
                <div class="col-lg-6">
                    <div class="add-new-btn">
                        <a href="{{ route('loan.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus"></i>{{ __('Add New Loan') }}</a>
                    </div>
                </div>
            </div>
            @if (Session::has('message'))
              <div class="alert alert-danger">{{ Session::get('message') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="loantable-2">
                  <thead>
                    <tr>
                      <th>{{ __('ID') }}</th>
                      
                      <th>{{ __('Loan ID') }}</th>
                      <th>{{ __('Customer Name') }}</th>
                      <th>{{ __('Loan Amount') }} </th>
                      <th>{{ __('Interest') }}</th>
                      <th>{{ __('Loan Duration') }}</th>
                      <th>{{ __('Loan Status') }}</th>
                      <th>{{ __('Created By')}} </th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($loans as $loan)
                    <tr>
                      <td> {{ $loan->id }} </td>
                      <td>{{ $loan->loan_id }}</td>
                      <td>
                        {{ $loan->customer->first_name }} {{ $loan->customer->last_name }}
                      </td>
                      <td>{{ $loan->amount }}</td>
                      <td>{{ $loan->interest }}</td>
                      <td>{{ $loan->duration }}</td>
                      <td>
                        @if($loan->status == "pending")
                        <span class="badge badge-warning">{{ $loan->status }}</span>
                        @elseif($loan->status == "approved")
                        <span class="badge badge-success">{{ $loan->status }}</span>
                        @elseif($loan->status == "rejected")
                        <span class="badge badge-danger">{{ $loan->status }}</span>
                        @else
                        <span class="badge badge-info">{{ $loan->status }}</span>
                        @endif
                      </td>
                      <!--get the user who created the loan using created_by column-->

                      <td>{{ $loan->creator->first_name }} {{ $loan->creator->last_name }}</td>


                      <td>
                        <div class="dropdown d-inline">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('Action') }}
                          </button>
                          <div class="dropdown-menu">
                            <!--show the loan details-->
                            <a class="dropdown-item has-icon" href="{{ route('loan.show',$loan->loan_id) }}"><i class="fa fa-eye"></i>{{ __('View') }}</a>
                            <a class="dropdown-item has-icon" href="{{ route('loan.edit', $loan->loan_id) }}"><i class="fa fa-edit"></i>{{ __('Edit') }}</a>
                            <a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-id={{ $loan->id }}><i class="fa fa-trash"></i>{{ __('Delete') }}</a>
                            <!-- Delete Form -->
                            <form class="d-none" id="delete_form_{{ $loan->id }}" action="{{ route('loan.destroy', $loan->id) }}" method="POST">
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
                {{ $loans->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
</div>
@endsection

//chat openeners for texting

@push('js')
<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
@push('js')
<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#loantable-2').DataTable({
            responsive: true,
            "order": [[ 0, "desc" ]],
        });
    });
</script>
@endpush
@endpush