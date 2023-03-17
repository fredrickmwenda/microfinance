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
               @if($loan->payment_type == 'installment')
               <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#schedule">{{
                  __("Loan Payment Schedule")
                  }}</a>
               </li>
               @endif
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
                     <!-- <tr>
                        <td>{{ __("Account") }}</td>
                        <td>{{ $loan->customer->email }}</td>
                     </tr> -->
                     <tr>
                        <td>{{ __("Status ") }}</td>
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

                           @if($loan->status == "approved")
                              @can('loan.disburse')
                              <a class="btn btn-outline-primary btn-sm" href="{{ route('disburse.loan', $loan->loan_id) }}"><i class="icofont-check-circled"></i> {{ __("Click to Disburse") }}</a>
                              @endcan
                           @endif   
                           
                           @if($loan->status == "pending")
                              @can('loan.approve')
                              <a class="btn btn-outline-primary btn-sm" href="{{ route('loan.approve', $loan->id) }}"><i class="icofont-check-circled"></i> {{ __("Click to Approve") }}</a>
                              <!-- on click of reject button, show a modal to enter reason for rejection -->
                              @endcan
                              @can('loan.reject')
                              <a class="btn btn-outline-danger btn-sm float-right" href="#" data-toggle="modal" data-target="#rejectModal"><i class="icofont-close-circled"></i> {{ __("Click to Reject") }}</a>
                              @endcan  
                           @endif
                                                     
                        </td>
                     </tr>
                     @if($loan->status == 'disbursed' || $loan->status == 'closed' || $loan->status == 'active' || $loan->status == 'overdue' || $loan->status == 'defaulted' || $loan->status == 'written_off')
                     <tr>
                        @if($loan->payment_type == "installment")
                        <td>{{ __("First Payment Date") }}</td>
                        <td>{{ $loan->first_payment_date }}</td>
                        @else
                        <td>{{ __("Payment Date") }}</td>
                        <td>{{ $loan->end_date }}</td>
                        @endif
                     </tr>
                     <tr>
                        <td>{{ __("Release Date") }}</td>
                        <td>
                           {{ $loan->start_date }}
                        </td>
                     </tr>
                     @endif
                     <tr>
                        <td>{{ __("Applied Amount") }}</td>
                        <td>
                           {{ $loan->amount }}
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
                           <!-- Total paid is total_payble -remaining_balance -->
                           {{ $loan->total_payable - $loan->remaining_balance }}
                        
                        </td>
                     </tr>
                     <tr>
                        <td>{{ __("Due Amount") }}</td>
                        <td class="text-danger">
                           {{ $loan->remaining_balance}}
                        </td>
                     </tr>
                     <!-- <tr>
                        <td>{{ __("Late Payment Penalties") }}</td>
                        <td>{{ $loan->late_payment_fee }} </td>
                     </tr> -->
                     <!-- check if loan attachments exist, we are checking in a collection -->
                     @if($loan_attachments)
                     <tr>
                        <td>{{ __("Attachment") }}</td>
                        <td>
                           
                           <!-- loan attachments can be multiple, so we need to loop through them -->
                           @foreach($loan_attachments as $attachment)
                           <a href="{{ asset('public/loan_attachments'.$attachment->attachment_name) }}" target="_blank">{{ $attachment->attachment_name }}</a>
                           @endforeach
                           <!-- storage path is storage/app/public/loan_attachments -->
                           <!-- <a href="{{ asset('storage/loan_attachments/'.$loan->loan_attachments) }}" target="_blank">{{ $loan->loan_attachments }}</a> -->
                        </td>
                     </tr>
                     @endif
                     @if($loan->status == "approved")
                     <tr>
                        <td>{{ __("Approved Date") }}</td>
                        <td>{{ $loan->approved_at }}</td>
                     </tr>
                     <tr>
                        <td>{{ __("Approved By") }}</td>
                        <td>{{ $loan->approver->first_name }} {{ $loan->approver->last_name }}</td>
                     </tr>
                     @endif
                     @if(isset($loan->loan_purpose))
                     <tr>
                        <td>{{ __("Description") }}</td>
                        <td>{{ $loan->loan_purpose }}</td>
                     </tr>
                     @endif

                  </table>
               </div>
               <div class="tab-pane fade mt-4" id="schedule">
               <div class="table-responsive">
                  <table class="table table-bordered data-table">
                     <thead>
                        <tr>
                           <th>{{ __("Date") }}</th>
                           <th class="text-right">{{ __("Amount to Pay") }}</th>
                           <!-- <th class="text-right">{{ __("Penalty") }}</th> -->
                           <th class="text-right">{{ __("Principal Amount") }}</th>
                           <th class="text-right">{{ __("Interest") }}</th>
                           <th class="text-right">{{ __("Balance") }}</th>
                           <th class="text-center">{{ __("Status") }}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if ($loan->status === 'active' || $loan->status === 'closed' || $loan->status === 'disbursed')
                           @php
                              $duration = $loan->duration;
                              
                              $weeks = floor($duration / 7);
                              
                              $extra_days = $duration % 7;
                              if ($extra_days > 0) {
                                 $weeks += 1;
                              }                           
                              $amount_to_pay = $loan->total_payable / $weeks;
                              $amount_to_pay = number_format($amount_to_pay, 2);
                              $interest = $loan->interest / $weeks;
                              $interest = number_format($interest, 2);
                              $principal_amount = $amount_to_pay - $interest;
                              $balance = $loan->remaining_balance;
                              $start_date = \Carbon\Carbon::parse($loan->start_date);
                              $end_date = $start_date;
                           @endphp
                           <tr>
                           <!-- date("Y-m-d", $start_date) -->
                              <td>{{ $start_date->format('Y-m-d') }}</td>
                              <td>{{ $loan->total_payable }}</td>
                              <!-- <td>0</td> -->
                              <td>0</td>
                              <td>0</td>
                              <td>{{ $balance }}</td>
                              <td>Disbursement Week</td>
                           </tr>

                           @for ($i = 0; $i < $weeks; $i++)
                              @php
                                 $end_date->addDays(7);
                                 $balance -= $amount_to_pay;
                                 if (is_float($balance)) {
                                    $balance = round($balance, 2);
                                    if (substr($balance, 0, 2) == "0.") {
                                          $balance = 0;
                                    }
                                 }
                              @endphp
                              <tr>
                                 <td>{{ $end_date->format('Y-m-d') }}</td>
                                 <td>{{ $amount_to_pay }}</td>
                                 <!-- <td>0</td> -->
                                 <td>{{ $principal_amount }}</td>
                                 <td>{{ $interest }} </td>
                                 <td>   
 
                                       {{ $balance }}
                            
                                  </td>
                                 <td>{{$loan->status}}</td>
                              </tr>
                           @endfor

                        @endif
                     </tbody>
                  </table>
               </div >  g
               </div>
               <div class="tab-pane fade mt-4" id="loan_payments">
                  <table class="table table-bordered data-table">
                     <thead>
                        <tr>
                           <th>{{ __("Loan ID") }}</th>
                           <th class="text-right">{{ ("Customer Name") }}</th>
                           <th class="text-right">{{ __("Amount Paid ") }}</th>
                           <th class="text-right">{{ __("Remaining Balance") }}</th>
                           <th class="text-right">{{ __("Payment Date") }}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($loan_payments as $payment)
                        <tr>
                           <td>{{ $payment->loan_id }}</td>
                           <td class="text-right">
                              {{ $payment->loan->customer->first_name }} {{ $payment->loan->customer->last_name }}
                           </td>
                           <td class="text-right">
                              {{ $payment['amount'] }}
                           </td>
                           <td class="text-right">
                              {{ $payment['balance'] }}
                           </td>
                           <td class="text-right">
                              <!-- payment_date is a string, so we need to convert it to a date -->
                              {{ date('d-m-Y', strtotime($payment['payment_date'])) }}
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
                              <label for="rejection_reason">{{ __("Reason") }}</label>
                              <textarea
                                 name="rejection_reason"
                                 id="rejection_reason"
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



