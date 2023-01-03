@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Approved Loans List'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                  <h4>{{ __('Approved Loans') }}</h4>
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
                <table class="table table-striped" id="loanPendingTable-2">
                  <thead>
                    <tr>
                      
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
                      <td>{{ $loan->loan_id }}</td>
                      <td>
                        {{ $loan->customer->first_name }} {{ $loan->customer->last_name }}
                      </td>
                      <td>{{ $loan->amount }}</td>
                      <td>{{ $loan->interest }}</td>
                      <td>{{ $loan->duration }}</td>
                      <td>
                        @if($loan->status == "approved")
                        <span class="badge badge-success">{{ $loan->status }}</span>
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
               
            </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('js')

<script src="{{ asset('backend/admin/assets/js/sweetalert2.all.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#loanPendingTable-2').DataTable({
            responsive: true,
            "order": [[ 0, "desc" ]],
        });
    });
</script>
@endpush
