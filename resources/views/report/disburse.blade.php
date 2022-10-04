@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Disbursement Report'])
@endsection

@section('content')

<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                      <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap">
                      <div class="card-header">
                        <h4>{{ __('Total Disbursements') }}</h4>
                      </div>
                      <div class="card-body">
                        {{ $total_disbursements }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                      <i class="fas fa-circle"></i>
                    </div>
                    <div class="card-wrap">
                      <div class="card-header">
                        <h4>{{ __('Active Disbursements') }}</h4>
                      </div>
                      <div class="card-body">
                        {{ $active_disbursements }}
                      </div>
                    </div>
                  </div>
                </div>      
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-danger">
                        <i class="far fa-circle"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{ __('Inactive Disbursements') }}</h4>
                        </div>
                        <div class="card-body">
                          {{ $banned_disbursements }}
                        </div>
                      </div>
                    </div>
                  </div>            
               </div>
              <div class="col-lg-8">
                <form method="get" action="{{ route('admin.report.disbursements.search') }}">
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="start_date">{{ __('Start Date') }}</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" value="{{ $start_date }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="end_date">{{ __('End Date') }}</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" value="{{ $end_date }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="status">{{ __('Status') }}</label>
                        <select class="form-control" name="status" id="status">
                          <option value="">{{ __('Select Status') }}</option>
                          <option value="1" {{ $status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                          <option value="0" {{ $status == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                    </div>
                    </div>
                </form>

                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <label for="status">{{ __('Export') }}</label>
                    <a href="{{ route('admin.report.disbursements.export', ['start_date' => $start_date, 'end_date' => $end_date, 'status' => $status]) }}" class="btn btn-success">{{ __('Export') }}</a>
                  </div>
                </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-striped" id="table-1">
                    <thead>
                      <tr>
                        <th class="text-center">
                          #
                        </th>
                        <th>{{ __('Disbursement Name') }}</th>
                        <th>{{ __('Disbursement Amount') }}</th>
                        <th>{{ __('Disbursement Date') }}</th>
                        <th>{{ __('Disbursement Status') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($disbursements as $key => $disbursement)
                      <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $disbursement->disbursement_name }}</td>
                        <td>{{ $disbursement->disbursement_amount }}</td>
                        <td>{{ $disbursement->transaction_amount }}</td>
                        <td>
                          @if($disbursement->transaction_status == 1)
                          <div class="badge badge-success">{{ __('Active') }}</div>
                          @else
                          <div class="badge badge-danger">{{ __('Inactive') }}</div>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="float-right">
                  {{ $disbursements->links() }}
                </div>
                </div>
                </div>
            </div>
</div>
@push('js')

<!-- 
<script src="{{ asset('assets/backend/admin/assets/js/datatables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script> -->
    <!-- Buttons -->
    <script src="{{ asset('assets/backend/admin/assets/js/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/backend/admin/assets/js/datatables/buttons.colVis.min.js') }}"></script>

    <!-- Responsive -->
    <!-- <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script> -->

    <!-- Custom Export Lib -->
    <!-- <script src="{{ asset('assets/libs/datatables-export/dataTables.export.js') }}"></script> -->

    <!-- Datatables init -->
    <script>
      $(document).ready(function() {
        $("#table-1").DataTable({
          dom: "Bfrtip",
          buttons: [
            "copy",
            "csv",
            "excel",
            "pdf",
            "print",
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
