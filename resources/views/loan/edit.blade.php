@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{__('Loan Edit')}}</h4>
            </div>

            @if ($errors->any())
              <div class="alert alert-danger">
                  <strong>{{ __('Whoops!') }}</strong> {{ __('There were some problems with your input.') }}<br><br>
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <form method="POST" action="{{ route('loan.update', $loan_edit->loan_id) }}" class="basicform" enctype="multipart/form-data">
              @csrf
              @method('put')
              <div class="card-body">
                <!--set customer input to  readonly-->
                <div class="form-row mb-3">
                  <div class="form-group col-md-6">
                    <label for="customer_id">{{ __('Customer Name') }}</label>
                    <input type="text" class="form-control" id="customer_id" name="customer_id" value="{{ $loan_edit->customer->first_name }} {{ $loan_edit->customer->last_name }}" readonly>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="loan_amount">{{ __('Loan Amount') }}</label>
                    <input type="text" class="form-control" id="loan_amount" name="loan_amount" value="{{ $loan_edit->loan_amount }}" required>
                  </div>
                </div>
                <div class="form-row mb-3">
                  <div class="form-group col-md-6">
                    <label for="loan_interest">{{ __('Loan Interest') }}</label>
                    @php
                      $loan_interest = $loan_edit->loan_interest;
                      $loan_interest = ($loan_interest * 100)/$loan_edit->loan_amount;
                      //convert to no decimal
                      $loan_interest = number_format($loan_interest, 0, '.', '');
                    @endphp
                    <input type="text" class="form-control" id="loan_interest" name="loan_interest" value="{{ $loan_interest }}" readonly>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="loan_duration">{{ __('Loan Duration') }}</label>

                    <input type="text" class="form-control" id="loan_duration" name="loan_duration" value="{{ $loan_edit->loan_duration }}" required>
                  </div>
                </div>
                <!--processing fee and loan creator-->
                <div class="form-row mb-3">
                  <div class="form-group col-md-6">
                    <label for="loan_processing_fee">{{ __('Loan Processing Fee') }}</label>
                    <input type="text" class="form-control" id="loan_processing_fee" name="processing_fee" value="{{ $loan_edit->processing_fee }}" readonly>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="loan_creator">{{ __('Loan Creator') }}</label>
                    <input type="text" class="form-control" id="loan_creator" name="created_by" value="{{ $loan_edit->creator->first_name }} {{ $loan_edit->creator->last_name }}" readonly>
                  </div>
                </div>
                
                <div class="form-row mb-3">
                  <div class="form-group col-md-6">
                    <label for="loan_status">{{ __('Loan Status') }}</label>
                    <select id="loan_status" name="loan_status" class="form-control">
                      <!--options are pending, approved, rejected,disbursed,active,closed, overdue->-->
                      <option value="pending" {{ $loan_edit->loan_status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                      <option value="approved" {{ $loan_edit->loan_status == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                      <option value="rejected" {{ $loan_edit->loan_status == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                      <option value="disbursed" {{ $loan_edit->loan_status == 'disbursed' ? 'selected' : '' }}>{{ __('Disbursed') }}</option>
                      <option value="active" {{ $loan_edit->loan_status == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                      <option value="closed" {{ $loan_edit->loan_status == 'closed' ? 'selected' : '' }}>{{ __('Closed') }}</option>
                      <option value="overdue" {{ $loan_edit->loan_status == 'overdue' ? 'selected' : '' }}>{{ __('Overdue') }}</option>
                    </select>
                  </div>

                  <!--loan payment status-->
                  <!--check if loan status is either disbursed, active or overdue first-->
                  @if($loan_edit->loan_status == 'disbursed' || $loan_edit->loan_status == 'active' || $loan_edit->loan_status == 'overdue')
                  <div class="form-group col-md-6">
                    <label for="loan_payment_status">{{ __('Loan Payment Status') }}</label>
                    <select id="loan_payment_status" name="loan_payment_status" class="form-control">
                      <!--options are pending, paid, overdue-->
                      <option value="not_paid" {{ $loan_edit->loan_payment_status == 'not_paid' ? 'selected' : '' }}>{{ __('Not Paid') }}</option>
                      <option value="paid" {{ $loan_edit->loan_payment_status == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                      <option value="overdue" {{ $loan_edit->loan_payment_status == 'overdue' ? 'selected' : '' }}>{{ __('Overdue') }}</option>
                    </select>
                  </div>
                  @endif
                </div>

                <div class="form-group">          
                  <label>{{ __('Attachments *not required, but you can add multiple files for loan request credibility') }}</label>
                  <!--get loan attachments-->
                  @if(isset($loan_edit->loan_attachments))
                  <!--show previous attachments in an input field-->
                  <input type="file" class="dropify" name="loan_attachments[]" multiple data-default-file="{{ asset('storage/loan_attachments/'.$loan_edit->loan_attachments) }}" data-height="100" data-allowed-file-extensions="jpg png jpeg webp pdf doc docx xls xlsx" data-max-file-size="10M" data-show-remove="false" data-errors-position="outside"/>
                  @else
                  <input type="file" class="dropify" name="loan_attachments[]" multiple data-height="100" data-allowed-file-extensions="jpg png jpeg webp pdf doc docx xls xlsx" data-max-file-size="10M" data-show-remove="false" data-errors-position="outside"/>
                  @endif
                </div>

                <div class="form-group">
                  <label> {{__('Loan Purpose')}} </label>
                  <textarea class="form-control" name="loan_purpose" rows="3" required>{{ $loan_edit->loan_purpose }}</textarea>
                </div>
                 



              </div>
              <div class="card-footer">              
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn">Submit</button>
                  </div>
                </div>
              </div>
            
            </form>
        </div>
    </div>
</div>

@endsection


@push('js')
<script>
  // $(document).ready(function() {
    // $('#customer_id').select2({
    //   // theme: 'bootstrap4'
    //   placeholder: 'Select Customer',
    //   allowClear: true,
    //   width: '100%',
    // });
  // });
  //show installment fields when installment is selected
  // $('#loan_payment_type').on('change', function() {
  //   if (this.value == 'installment') {
  //     $('#installment_fields').show();
  //   } else {
  //     $('#installment_fields').hide();
  //   }
  // });



  $('.dropify').dropify({
    messages: {
      'default': 'Drag and drop a file here or click',
      'replace': 'Drag and drop or click to replace',
      'remove': 'Remove',
      'error': 'Ooops, something wrong appended.'
    }
  });

  //Duration should be 1 or 1/2 months in days, it should not be more than 45 days
  $('input[name="loan_duration"]').on('change', function() {
    if (this.value > 45) {
      alert('Duration should not be more than 45 days');
      this.value = '';
    }
    //if the duration is 30 days or less, then the interest is 20%. If it is more than that its 30%
    if (this.value <= 30) {
      //get the loan amount
      // var loan_amount = $('input[name="loan_amount"]').val();
      //calculate the interest
      // var interest = (loan_amount * 20) / 100;
      //set the intere

      $('input[name="loan_interest"]').val(20);
    } else {
      $('input[name="loan_interest"]').val(30);
    }
  });

  $('input[name="loan_amount"]').on("change", function(){
            var loan_amount = $(this).val();
            //if the value is less than 3000 warn the user
            if(loan_amount < 3000){
                alert('Loan amount must be greater than 3000');
            }
            //if the value is between 3000 to 10000 set the interest rate to 550
            else if(loan_amount >= 3000 && loan_amount <= 10000){
                // $('input[name="loan_interest"]').val(500);
                $('input[name="processing_fee"]').val(500);
                
            }
            //from 10000 and above the interest rate increases by 50
            else if(loan_amount > 10000){
                var interest_rate = 500 + (loan_amount - 10000) / 1000 * 50;
                //incase the interest rate has decimals round it to the nearest integer
                interest_rate = Math.round(interest_rate);
                $('input[name="processing_fee"]').val(interest_rate);
            }

        
            // var interest_rate = $('input[name="interest_rate"]').val();
            // var interest_amount = (loan_amount * interest_rate) / 100;
            // $('input[name="interest_amount"]').val(interest_amount);
        });
</script>

@endpush
