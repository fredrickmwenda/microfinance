@extends('layouts.backend.app')
@push('css')
<style>
#loan_cust{
  padding-top: 12px;
  height: 57px;
}

.label-form{
  font-weight: 600;
    color: #34395e;
    font-size: 14px;
    letter-spacing: .5px;
}

</style>

@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{__('Loan Request')}}</h4>
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
            <form method="POST" action="{{ route('loan.store') }}" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="form-row mb-3">
                  <div class="col-lg-8 col-md-8 col-sm-12">
                    <label class="label-form">{{ __('Customer Name') }}</label>
                    
                    <select class="form-control" id="customer_id" name="customer_id">
                      <option value="">{{ __('Select Customer') }}</option>
                      @foreach($customers as $customer)
                      <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                      @endforeach
                    </select>
                  </div>
                 
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <label></label>
                    <a href="{{ route('customer.create') }}" class="btn btn-primary btn-block mt-2" id="loan_cust">{{ __('Create New Customer') }}
                      <i class="fas fa-plus"></i>
                    </a>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Loan Amount') }}</label>
                        <input type="number" step="any" class="form-control" name="loan_amount" placeholder="Loan Amount" required>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Processing Fee') }}</label>
                      <input type="number" step="any" class="form-control" name="processing_fee" placeholder="Processing Fee" required readonly>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>{{ __('Duration *set duration in days') }}</label>
                        <input type="number" step="any" class="form-control" name="duration" placeholder="Duration" required>
                    </div>
                  </div>
                   <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label>{{ __('Interest Rate %') }}</label>
                        <!--interest is in percentage form-->
                        <input type="number" step="any" class="form-control" name="loan_interest" placeholder="Interest" required readonly>
                      </div>
                   </div>
           
                </div>
  
                <div class="form-row">


                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label>{{ __('Loan Payment Method') }}</label>
                      <select name="loan_payment_type" class="form-control" id="loan_payment_type">
                        <option value="">{{ __('Select Payment Method') }}</option>
                        <option value="one_time">{{ __('One Time Payment') }}</option>
                        <option value="installment">{{ __('Installment') }}</option>
                      </select>
                    </div>
                  </div>
                    
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">                       
                      <label>{{ __('Late Payment Fee') }}</label>
                      <input type="number" step="any" class="form-control" name="late_fee" placeholder="Late Payment Fee">
                    </div>
                  </div>
                </div>

                                <!--hidden fields, only show when installment is selected-->
                <div class="form-row" id="installment_fields" style="display: none;">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <!--number of installment, they are up to 6 installments-->
                      <label>{{ __('Number of Installment') }}</label>
                      <select name="installments" class="form-control" id="installment">
                        <option value="">{{ __('Select Number of Installment') }}</option>
                        <option value="1">{{ __('1') }}</option>
                        <option value="2">{{ __('2') }}</option>
                        <option value="3">{{ __('3') }}</option>
                        <option value="4">{{ __('4') }}</option>
                        <option value="5">{{ __('5') }}</option>
                        <option value="6">{{ __('6') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <!--first installment date-->
                      <label>{{ __('First Installment Date') }}</label>
                      <input type="date" class="form-control" name="first_installment_date" placeholder="First Installment Date" >
                    </div>
                  </div>
                </div>

                <div class="form-group">          
                  <label>{{ __('Attachments *not required, but you can add multiple files for loan request credibility') }}</label>
                  <input type="file" class="dropify" name="attachments" data-height="100" data-allowed-file-extensions="jpg png jpeg webp pdf doc docx xls xlsx" data-max-file-size="10M" data-show-remove="false" data-errors-position="outside" multiple>
                </div>

                <div class="form-group">
                  <label> {{__('Loan Purpose')}} </label>
                  <textarea class="form-control" name="loan_purpose"></textarea>
                </div>
              </div>
                <!-- </div> -->
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
@push('js')
<script>
  $(document).ready(function() {
    $('#customer_id').select2({
      // theme: 'bootstrap4'
      placeholder: 'Select Customer',
      allowClear: true,
      width: '100%',
    });
  });
  //show installment fields when installment is selected
  $('#loan_payment_type').on('change', function() {
    if (this.value == 'installment') {
      $('#installment_fields').show();
    } else {
      $('#installment_fields').hide();
    }
  });



  $('.dropify').dropify({
    messages: {
      'default': 'Drag and drop a file here or click',
      'replace': 'Drag and drop or click to replace',
      'remove': 'Remove',
      'error': 'Ooops, something wrong appended.'
    }
  });

  //Duration should be 1 or 1/2 months in days, it should not be more than 45 days
  $('input[name="duration"]').on('change', function() {
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

@endsection



