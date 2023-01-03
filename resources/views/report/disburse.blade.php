@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Disbursement Report'])
@endsection

@section('content')

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Total Disbursements: ({{ $total_disburses }})</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <form method="GET" action="{{ route('disburse.report') }}">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group row">
                      <div class="col-lg-2 d-flex align-items-center">
                        {{ __('From:') }}
                      </div>
                      <div class="col-lg-10">
                        <input type="datetime-local" class="form-control" name="from_date" >                          
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group row">
                      <div class="col-lg-2 d-flex align-items-center">
                        {{ __('To:') }}
                      </div>
                      <div class="col-lg-10 input-group">
                        <input type="datetime-local" class="form-control" name="to_date">
                      </div>
                    </div>
                  </div>
                  
                  <!-- <div class="col-lg-2">
                    <div class="form-group row">
                      <div class="col-lg-3 d-flex align-items-center">
                        {{ __('Status:') }}
                      </div>
                      <div class="col-lg-9">
                          <select class="form-control" name="status">
                              <option value="">{{ __('Select Status') }}</option>
                              <option value="active">{{ __('Active') }}</option>
                              <option value="in-active">{{ __('Inactive') }}</option>
                          </select>
                      </div>
                    </div>
                  </div> -->

                  <div class="col-lg-4">
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <div class="input-group">
                          <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>Filter</button>
                          <a href="{{ route('disburse.report') }}" class="btn btn-danger ml-2"><i class="fas fa-sync-alt"></i>Clear</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped" id="table-disburse">
              <thead>
                <tr>
                  <th class="text-center">
                    #
                  </th>
                  <th>{{ __('Disbursement ID') }}</th>
                  <th>{{ __('Disbursed Amount') }}</th>
                  <th> {{ __('Customer') }}</th>
                  <th>{{ __('Loan Amount') }}</th>
                  <th>{{ __('Disburser' )}}</th>                      
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Date') }}</th>
                </tr>
              </thead>
              <tbody>
                <!-- check if disburses is empty -->
                @if(!empty($disburses))
                  @forelse($disburses as $key => $disbursement)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $disbursement->disbursement_code }}</td>
                    <td>{{ $disbursement->disbursement_amount }}</td>
                    <td>{{ $disbursement->disbursedTo->first_name }} {{ $disbursement->disbursedTo->last_name }}</td>
                    <td>{{ $disbursement->loan->amount }}</td>
                    <td>{{ $disbursement->disburser->first_name }} {{ $disbursement->disburser->last_name }}</td>

                    <td>
                      @if($disbursement->status == 'success')
                        <span class="badge badge-success">{{ $disbursement->status }}</span>
                      @elseif($disbursement->status == 'failure')
                        <span class="badge badge-danger">{{ $disbursement->status }}</span>
                      @else
                        <span class="badge badge-warning">{{ $disbursement->status }}</span>
                      @endif
                    </td>
                    <td>{{ $disbursement->created_at->format('d M Y') }}</td>
                  </tr>
                  @endforeach
                @else
                <p> No Disbursement Found </p>
                @endif
              </tbody>
            </table>
            {{ $disburses->links() }}
          </div>  
        </div>
      </div>
    </div>
</div>
@endsection
@push('js')

    <script src="{{ asset('assets/backend/admin/assets/js/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/datatables/buttons.colVis.min.js') }}"></script>


  <script>
    $(document).ready(function() {
      $("#table-disburse").DataTable({
        dom: "Bfrtip",
        buttons: [
          "copy",
          {
            extend: "csv",
            title: "Disbursement Report",
          },
          {
            extend: "excel",
            title: "Disbursement Report",
          },
          {
            extend: "pdf",
            title: "Disbursement Report",
          },
          {
            extend: "print",
            title: "Disbursement Report",
          },
          "colvis"
        ],
        responsive: true,
        language: {
          searchPlaceholder: "Search..."
        }
      });
    });
  </script>

@endpush
