@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{__('Loan Calculator')}}</h4>
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
            <form method="POST" action="#" class="basicform_with_reset">
              @csrf
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group">
                                <label>{{ __('Loan Amount') }}</label>
                                <input type="number" step="any" class="form-control" name="loan_amount" placeholder="Loan Amount" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="interest-rate">{{ __('Interest Rate') }}</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="interest_rate" placeholder="Interest Rate" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"></span>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Interest Type') }}</label>
                                <select name="interest_type" class="form-control" id="interest_type">
                                    <option value="">{{ __('Select interest type') }}</option>
                                    <option value="fixed">{{ __('Fixed') }}</option>
                                    <option value="reducing">{{ __('Reducing') }}</option>
                                    <option value="compound">{{ __('Compound') }}</option>
                                    <option value="flat">{{ __('Flat') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-row">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Loan Term') }}</label>
                                <input type="number" step="any" class="form-control" name="loan_term" placeholder="Loan Term" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Loan Term Type') }}</label>
                                <select name="loan_term_type" class="form-control" id="loan_term_type">
                                    <option value="">{{ __('Select loan term type') }}</option>
                                    <option value="days">{{ __('Days') }}</option>
                                    <option value="weeks">{{ __('Weeks') }}</option>
                                    <option value="months">{{ __('Months') }}</option>
                                    <option value="years">{{ __('Years') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Repayment Frequency') }}</label>
                                <select name="repayment_frequency" class="form-control" id="repayment_frequency">
                                    <option value="">{{ __('Select repayment frequency') }}</option>
                                    <option value="daily">{{ __('Daily') }}</option>
                                    <option value="weekly">{{ __('Weekly') }}</option>
                                    <option value="monthly">{{ __('Monthly') }}</option>
                                    <option value="yearly">{{ __('Yearly') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Start Date') }}</label>
                                <input type="date" class="form-control" name="start_date" placeholder="Start Date" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('First Payment Date') }}</label>
                                <input type="date" class="form-control" name="first_payment_date" placeholder="First Payment Date" required>
                            </div>
                        </div>
                        <!--late payment charge-->
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Late Payment Charge') }}</label>
                                <input type="number" step="any" class="form-control" name="late_payment_charge" placeholder="Late Payment Charge" required>
                            </div>
                        </div>
                        <!-- <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Grace Period') }}</label>
                                <input type="number" step="any" class="form-control" name="grace_period" placeholder="Grace Period" required>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" type="submit">{{ __('Calculate') }}</button>
                </div>

                   

          </form>
      </div>
  </div>
</div>
@push('js')
<script>
    $(document).ready(function(){
         // on entry of loan amount calculate the interest
        $('input[name="loan_amount"]').on("change", function(){
            var loan_amount = $(this).val();
            //if the value is less than 3000 warn the user
            if(loan_amount < 3000){
                alert('Loan amount must be greater than 3000');
            }
            //if the value is between 3000 to 10000 set the interest rate to 550
            else if(loan_amount >= 3000 && loan_amount <= 10000){
                $('input[name="interest_rate"]').val(500);
            }
            //from 10000 and above the interest rate increases by 50
            else if(loan_amount > 10000){
                var interest_rate = 500 + (loan_amount - 10000) / 1000 * 50;
                //incase the interest rate has decimals round it to the nearest integer
                interest_rate = Math.round(interest_rate);
                $('input[name="interest_rate"]').val(interest_rate);
            }

        
            // var interest_rate = $('input[name="interest_rate"]').val();
            // var interest_amount = (loan_amount * interest_rate) / 100;
            // $('input[name="interest_amount"]').val(interest_amount);
        });
        // $('.basicform_with_reset').validate({
        //     rules: {
        //         loan_amount: {
        //             required: true,
        //             number: true,
        //         },
        //         interest_rate: {
        //             required: true,
        //             number: true,
        //         },
        //         interest_type: {
        //             required: true,
        //         },
        //         loan_term: {
        //             required: true,
        //             number: true,
        //         },
        //         loan_term_type: {
        //             required: true,
        //         },
        //         repayment_frequency: {
        //             required: true,
        //         },
        //         start_date: {
        //             required: true,
        //         },
        //         first_payment_date: {
        //             required: true,
        //         },
        //         late_payment_charge: {
        //             required: true,
        //             number: true,
        //         },
        //         // grace_period: {
        //         //     required: true,
        //         //     number: true,
        //         // },
        //     },
        // });
    });

</script>

@endpush
@endsection



