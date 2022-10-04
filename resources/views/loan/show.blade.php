@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
        <div class="card">
         <div class="card-header">
            <span class="panel-title">{{ __("View Loan Details") }}</span>
         </div>
         <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
               <li class="nav-item">
                  <a class="nav-link active" data-toggle="tab" href="#loan_details">{{
                  __("Loan Details")
                  }}</a>
               </li>
               <!-- <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#collateral">{{
                  __("Collateral")
                  }}</a>
               </li> -->
               <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#schedule">{{
                  __("Loan Payment Schedule")
                  }}</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#loan_payments">{{
                  __("Loan Payments")
                  }}</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#attachments">{{
                  __("Loan Attachments")
                  }}</a>
               </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
               <div class="tab-pane active" id="loan_details">
                  <!-- 
                  <div class="alert alert-warning mt-4">
                     <p>
                        {{ __("Add Loan ID, Release Date and First Payment Date before approving loan request") }}
                     </p>
                  </div>-->
                  <table class="table table-bordered mt-4">
                     <tr>
                        <td>{{ __("Loan ID") }}</td>
                        <td>{{ $loan->loan_id }}</td>
                     </tr>
                     <tr>
                        <td>{{ __("Borrower") }}</td>
                        <td>{{ $loan->customer->first_name }} {{ $loan->customer->last_name }}</td>
                     </tr>
                     <tr>
                        <td>{{ __("Account") }}</td>
                        <td>{{ $loan->customer->email }}</td>
                     </tr>
                     <tr>
                        <td>{{ __("Status ") }}</td>
                        <td>
                            @if($loan->loan_status == "pending")
                            <span class="badge badge-warning">{{ $loan->loan_status }}</span>
                            @elseif($loan->loan_status == "approved")
                            <span class="badge badge-success">{{ $loan->loan_status }}</span>
                            @elseif($loan->loan_status == "rejected")
                            <span class="badge badge-danger">{{ $loan->loan_status }}</span>
                            @endif

                            @if($loan->loan_status == "approved")
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('disburse.loan', $loan->loan_id) }}"><i class="icofont-check-circled"></i> {{ __("Click to Disburse") }}</a>
                              @endif   
                           
                           @if($loan->loan_status == "pending")
                           @can('loan_management.approve_loan')
                           <a class="btn btn-outline-primary btn-sm" href="{{ route('loan.approve', $loan->id) }}"><i class="icofont-check-circled"></i> {{ __("Click to Approve") }}</a>
                            <!-- on click of reject button, show a modal to enter reason for rejection -->
                            @endcan
                            @can('loan_management.reject_loan')
                           <a class="btn btn-outline-danger btn-sm float-right" href="#" data-toggle="modal" data-target="#rejectModal"><i class="icofont-close-circled"></i> {{ __("Click to Reject") }}</a>
                           @endcan
                           <!-- ><i class="icofont-close-line-circled"></i> {{ __("Click to Reject") }}</a> -->
                           @endif

                           <!-- @if($loan->loan_status == "approved") -->
                           <!--disburse loan button -->
                           <!-- @can('disburse.create') -->
                           <a class="btn btn-outline-primary btn-sm" href="{{ route('loan.disburse', $loan->id) }}"><i class="icofont-check-circled"></i> {{ __("Click to Disburse") }}</a>
                           <!-- @endcan -->
                           <!-- @endif -->
                           
                        </td>
                     </tr>
                     <tr>
                        @if($loan->loan_payment_type == "installment")
                        <td>{{ __("First Payment Date") }}</td>
                        <td>{{ $loan->first_payment_date }}</td>
                        @else
                        <td>{{ __("Payment Date") }}</td>
                        <td>{{ $loan->first_payment_date }}</td>
                        @endif
                     </tr>
                     <tr>
                        <td>{{ __("Release Date") }}</td>
                        <td>
                           {{ $loan->release_date != '' ? $loan->release_date : '' }}
                        </td>
                     </tr>
                     <tr>
                        <td>{{ __("Applied Amount") }}</td>
                        <td>
                           {{ $loan->loan_amount }}
                        </td>
                     </tr>
                     <tr>
                        <td>{{ __("Total Payable") }}</td>
                        <td>
                           {{ $loan->total_payable }}
                        </td>
                     </tr>
                     <tr>
                        <td>{{ __("Total Paid") }}</td>
                        <td class="text-success">
                           {{ $loan->total_paid}}
                        </td>
                     </tr>
                     <tr>
                        <td>{{ __("Due Amount") }}</td>
                        <td class="text-danger">
                           {{ $loan->total_payable - $loan->total_paid }}
                        </td>
                     </tr>
                     <tr>
                        <td>{{ __("Late Payment Penalties") }}</td>
                        <td>{{ $loan->late_payment_fee }} </td>
                     </tr>
                     <tr>
                        <td>{{ __("Attachment") }}</td>
                        <td>
                           {!! $loan->attachment == "" ? '' : '<a
                              href="'. asset('public/uploads/media/'.$loan->loan_attachments) .'"
                              target="_blank"
                              >'.__('Download').'</a
                              >' !!}
                        </td>
                     </tr>
                     @if($loan->loan_status == "approved")
                     <tr>
                        <td>{{ __("Approved Date") }}</td>
                        <td>{{ $loan->approved_at }}</td>
                     </tr>
                     <tr>
                        <td>{{ __("Approved By") }}</td>
                        <td>{{ $loan->approver->first_name }} {{ $loan->approver->last_name }}</td>
                     </tr>
                     @endif
                     <tr>
                        <td>{{ __("Description") }}</td>
                        <td>{{ $loan->loan_purpose }}</td>
                     </tr>
                     <!-- <tr>
                        <td>{{ __("Remarks") }}</td>
                        <td>{{ $loan->remarks }}</td>
                     </tr> -->
                  </table>
               </div>
               <!-- <div class="tab-pane fade" id="collateral">
                  <div class="card">
                     <div class="card-header d-flex align-items-center">
                        <span>{{ __("All Collaterals") }}</span>
                        <a
                           class="btn btn-primary btn-sm ml-auto"
                           href="#"
                           ><i class="icofont-plus-circle"></i>
                        {{ __("Add New Collateral") }}</a
                           >
                     </div>
                     <div class="card-body">
                        <div class="table-responsive">
                           <table class="table table-bordered mt-2">
                              <thead>
                                 <tr>
                                    <th>{{ __("Name") }}</th>
                                    <th>{{ __("Collateral Type") }}</th>
                                    <th>{{ __("Serial Number") }}</th>
                                    <th>{{ __("Estimated Price") }}</th>
                                    <th class="text-center">{{ __("Action") }}</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($loancollaterals as $loancollateral)
                                 <tr data-id="row_{{ $loancollateral->id }}">
                                    <td class="name">{{ $loancollateral->name }}</td>
                                    <td class="collateral_type">
                                       {{ $loancollateral->collateral_type }}
                                    </td>
                                    <td class="serial_number">
                                       {{ $loancollateral->serial_number }}
                                    </td>
                                    <td class="estimated_price">
                                       {{ $loancollateral->estimated_price }}
                                    </td>
                                    <td class="text-center">
                                       <div class="dropdown">
                                          <button
                                             class="btn btn-primary dropdown-toggle btn-sm"
                                             type="button"
                                             id="dropdownMenuButton"
                                             data-toggle="dropdown"
                                             aria-haspopup="true"
                                             aria-expanded="false"
                                             >
                                          {{ __("Action") }}
                                          </button>
                                          <form
                                             action="#"
                                             method="post"
                                             >
                                             {{ csrf_field() }}
                                             <input
                                                name="_method"
                                                type="hidden"
                                                value="DELETE"
                                                />
                                             <div
                                                class="dropdown-menu"
                                                aria-labelledby="dropdownMenuButton"
                                                >
                                                <a
                                                   href="#"
                                                   class="
                                                   dropdown-item dropdown-edit dropdown-edit
                                                   "
                                                   ><i class="icofont-ui-edit"></i>
                                                {{ __("Edit") }}</a
                                                   >
                                                <a
                                                   href="#"
                                                   class="
                                                   dropdown-item dropdown-view dropdown-view
                                                   "
                                                   ><i class="icofont-eye-alt"></i>
                                                {{ __("View") }}</a
                                                   >
                                                <button
                                                   class="btn-remove dropdown-item"
                                                   type="submit"
                                                   >
                                                <i class="icofont-trash"></i>
                                                {{ __("Delete") }}
                                                </button>
                                             </div>
                                          </form>
                                       </div>
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div> -->
               <div class="tab-pane fade mt-4" id="schedule">
                  <table class="table table-bordered data-table">
                     <thead>
                        <tr>
                           <th>{{ __("Date") }}</th>
                           <th class="text-right">{{ __("Amount to Pay") }}</th>
                           <th class="text-right">{{ __("Penalty") }}</th>
                           <th class="text-right">{{ __("Principal Amount") }}</th>
                           <th class="text-right">{{ __("Interest") }}</th>
                           <th class="text-right">{{ __("Balance") }}</th>
                           <th class="text-center">{{ __("Status") }}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if(!isset($loan_payments))
                        @foreach($loan_payments as $repayment)
                        <tr>
                           <td>{{ $repayment->repayment_date }}</td>
                           <td class="text-right">
                              {{ $repayment['amount'] }}
                           </td>
                           <td class="text-right">
                              <!--repayment['penalty'], }} -->
                           </td>
                           <td class="text-right">
                              {{ $repayment ['amount']   }}
                           </td>
                           <td class="text-right">
                              <!-- / decimalPlace(repayment['interest']))  -->
                           </td>
                           <td class="text-right">
                              {{ $repayment['balance'] }}
                           </td>
                           <td class="text-center">
                              {!! $repayment['status'] == 1 ?
                              show_status(__('Paid'),'success') :
                              show_status(__('Unpaid'),'danger') !!}
                           </td>
                        </tr>
                        @endforeach
                        @endif
                     </tbody>
                  </table>
               </div>
               <div class="tab-pane fade mt-4" id="loan_payments">
                  <table class="table table-bordered data-table">
                     <thead>
                        <tr>
                           <th>{{ __("Date") }}</th>
                           <th class="text-right">{{ ("Principal Amount") }}</th>
                           <th class="text-right">{{ __("Interest") }}</th>
                           <th class="text-right">{{ __("Late Penalty") }}</th>
                           <th class="text-right">{{ __("Total Amount") }}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($loan_payments as $payment)
                        <tr>
                           <td>{{ $payment->paid_at }}</td>
                           <td class="text-right">
                              {{ $payment['amount_to_pay'] - $payment['interest'] }}
                           </td>
                           <td class="text-right">
                              {{ $payment['interest'] }}
                           </td>
                           <td class="text-right">
                              {{ $payment['late_penalties'] }}
                           </td>
                           <td class="text-right">
                              {{ $payment['amount_to_pay'] + $payment['late_penalties'] }}
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>

               <div class="tab-pane fade mt-4" id="attachments">
                  <!--show attachments in cards-->
                  <div class="row">
                     @foreach($loan_attachments as $attachment)
                    
                     <div class="col-md-3">
                        <div class="card">
                           <div class="card-body">
                              <div class="text-center">
                              <!-- href="{{ asset('storage/'.$attachment->file) }}" -->
                                 <a
                                    href="{{ asset('public/storage/loan_attachments/'.$attachment->attachment_name) }}"
                                    target="_blank"
                                    >
                                 <img
                                    src="{{ asset('public/storage/loan_attachments/'.$attachment->attachment_name) }}"
                                    class="img-fluid"
                                    alt="attachment"
                                    />
                                 </a>
                              </div>
                              <div class="text-center mt-2">
                                 <a
                                    href="{{ asset('public/storage/loan_attachments/'.$attachment->attachment_name) }}"
                                    target="_blank"
                                    >
                                 <h5 class="card-title">{{ $attachment->attachment_name }}</h5>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                     @endforeach
               </div>


            </div>

            <!-- modal to reject loan -->
            <div
               class="modal fade"
               id="rejectModal"
               tabindex="-1"
               role="dialog"
               aria-labelledby="rejectModalLabel"
               aria-hidden="true"
               >
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">
                        {{ __("Reject Loan") }}
                        </h5>
                        <button
                           type="button"
                           class="close"
                           data-dismiss="modal"
                           aria-label="Close"
                           >
                        <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        <form action="{{ route('loan.reject', $loan->id) }}"method="put">
                           {{ csrf_field() }}
                           <div class="form-group">
                              <label for="reject_reason">{{ __("Reason") }}</label>
                              <textarea
                                 name="reject_reason"
                                 id="reject_reason"
                                 cols="30"
                                 rows="5"
                                 class="form-control"
                                 ></textarea>
                           </div>
                           <div class="form-group">
                              <button
                                 type="submit"
                                 class="btn btn-primary btn-block"
                                 >
                              {{ __("Reject") }}
                              </button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>

         </div>
      </div>

        </div>
    </div>
</div>
@endsection



