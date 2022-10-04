@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Customer Report'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
         <!-- /.card-header -->
         <div class="card-header">
            <h3 class="card-title">Total Customers: ({{ $total_customers }})</h3>
            <!-- <div class="card-tools">
               <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                  <div class="input-group-append">
                     <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                  </div>
               </div>
            </div> -->
          </div>
        <div class="card-body">
            <div class="row">
                <!-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <h3 mb-3> {{ __('Total Customers') }}
                     <span class="badge badge-primary">{{ $total_customers }}</span>
                  </h3>
                </div> -->
   
         
              </div>
              <div class="col-lg-12">
                <form method="GET" action="{{ route('customers.report') }}">
                  <div class="row">
                      <div class="col-lg-3">
                        <div class="form-group row">
                          <!--Start with input-group-prepend--  with input-group-text-->
                          <!-- <div class= "col-lg-12">
                            <div class="input-group-prepend">
                              <span class="input-group-text" style="background-color: transparent!important;">From</span>
                              <input type="date" class="form-control" name="from_date" >
                            </div>
                          </div> -->


                          <div class="col-lg-2 d-flex align-items-center">
                            {{ __('From:') }}
                        </div>
                        <div class="col-lg-10">
                          <!--date time picker-->
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
                            <!-- <div class="input-group-append">                                            
                              <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                          </div> -->
                        </div>
                        </div>
                      </div>
                      <!--Filter by status-->
                      <div class="col-lg-2">
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
                      </div>

                      <!--Filter and clear button-->
                      <div class="col-lg-4">
                        <div class="form-group row">
                          <div class="col-lg-12">
                              <div class="input-group">
                                  <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i>Filter</button>
                                  <!--clear button have a refresh icon-->
                                  <a href="{{ route('customers.report') }}" class="btn btn-danger ml-2"><i class="fas fa-sync-alt"></i>Clear</a>
                                    <!-- <i class="fas fa-times"></i>Clear</a> -->
                              </div>
                          </div>
                        </div>
 
                      <!--Clear button-->
                      <!-- <div class="col-lg-2">
                        <div class="form-group row">
                     
                          <div class="col-lg-12">
                            <a href="{{ route('customers.report') }}" class="btn btn-danger"><i class="fas fa-times"></i>Clear</a>
                          </div>
                        </div>
                      </div> -->
                  </div>
              </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="customers-report">
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
                      <th>{{ __('Created At') }}</th>
                      <th>{{ __('Status') }}</th>
                      <th>{{ __('View') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($customers as $customer)
                    <tr>
                      <td>
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1">
                          <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                        </div>
                      </td>
                      <td>{{ $customer->first_name }} {{$customer->last_name}}</td>
                      <td>{{ $customer->email }}</td>
                      <td>{{ $customer->phone }}</td>
                      <td>{{ date('d-m-Y', strtotime($customer->created_at)) }}</td>
                      <td>
                        {{ $customer->status == 0 ? 'Inactive' : 'Active' }}
                      </td>
                      <td>
                        <a class="btn btn-primary" href="{{ route('customer.show', $customer->id) }}"><i class="fa fa-eye"></i>{{ __('View') }}</a>
                      </td>
                      
                    </tr>
                    @empty 
                       <p>{{ __('No customers!') }}</p>
                    @endforelse
                  </tbody>
                </table>
              {{ $customers->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
      </div>
    </div>
</div>
@endsection
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
        $("#customers-report").DataTable({
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



