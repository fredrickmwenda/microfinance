@extends('layouts.backend.app')

@section('content')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Transaction For Loan Payment') }}</h4>
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
            <form method="POST" action="{{ route('transaction.store') }}" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>{{ __('Customer Name') }}</label>
                    <!--select2-->
                    <select class="form-control select2" name="customer_id" id="customer_id">
                      <option value="">{{ __('Select Customer') }}</option>
                      @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <!--customer phone number-->
                        <label>{{ __('Customer Phone Number') }}</label>
                        <input type="text" class="form-control" name="phone" placeholder="Customer Phone Number" required readonly>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="loan_id" id="loan_id" value="">
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Total Loan') }}</label>
                            <input type="text" class="form-control" name="total_amount" placeholder="Loan Amount" required readonly>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                           <!--customer phone number-->
                           <label for="payment_method">{{ __('Payment Method') }}</label>
                          <select class="form-control" id="payment_method" name="payment_gateway_id" required>
                              <option value="">{{ __('Select Payment Method') }}</option>
                              <!--option is mpesa, visa, mastercard, paypal, etc-->
                              <option value="mpesa">{{ __('Mpesa') }}</option>
                              <option value="visa">{{ __('Visa') }}</option>
                              <option value="mastercard">{{ __('Mastercard') }}</option>
                              <option value="paypal">{{ __('Paypal') }}</option>
                          </select>
                        </div>
                    </div>
                  </div>

               
     

                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group ">
                        <label for="disburse_amount">{{ __('Paid Amount') }}</label>
                        <input type="text" class="form-control" id="paid_amount" name="transaction_amount" value="" required>
                    </div>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group ">
                        <label for="disburse_amount">{{ __('Transaction Reference') }}</label>
                        <input type="text" class="form-control" id="transaction_reference" name="transaction_reference" value="" required>
                    </div>
                  </div>                   
                </div>
                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group ">
                        <label for="payment_method">{{ __('Transaction Code *Unique for each transaction') }}</label>
                        <input type="text" class="form-control" id="transaction_code" name="transaction_code" value="" required>
                    </div>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group ">
                        <label for="disburse_amount">{{ __('Transaction Date & Time') }}</label>



                        <input type="datetime-local" class="form-control" id="transaction_date" name="transaction_date" value="" required>
                    </div>
                  </div>                   
                </div>



                <div id="visa" style="display: none;">
                <!--visa card details will be displayed if the payment method is visa-->
                    <div class="form-row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Card Number') }}</label>
                                <input type="text" class="form-control" name="card_number" placeholder="{{ __('Enter Card Number') }}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Card Holder Name') }}</label>
                                <input type="text" class="form-control" name="card_holder_name" placeholder="{{ __('Enter Card Holder Name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Expiry Date') }}</label>
                                <input type="text" class="form-control" name="expiry_date" placeholder="{{ __('Enter Expiry Date') }}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('CVV') }}</label>
                                <input type="text" class="form-control" name="cvv" placeholder="{{ __('Enter CVV') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="mpesa" style="display: none;">
                <!--mpesa details will be displayed if the payment method is mpesa-->
                    <div class="form-row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Mpesa Phone Number') }}</label>
                                <input type="text" class="form-control" name="mpesa_phone_number" placeholder="{{ __('Enter Mpesa Phone Number') }}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Mpesa Transaction Code') }}</label>
                                <input type="text" class="form-control" name="mpesa_transaction_code" placeholder="{{ __('Enter Mpesa Transaction Code') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div id="mastercard" style="display: none;"> -->


                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn">{{ __('Submit') }}</button>
                  </div>
                </div>
              </div>
            </form>
        </div>
    </div>
</div>

@push('js')

<script type="text/javascript">
    $(document).ready(function() {
        // $('#payment_method').on('change', function() {
        //     if (this.value == 'visa') {
        //         $("#visa").show();
        //         $("#mpesa").hide();
        //     } else if (this.value == 'mpesa') {
        //         $("#mpesa").show();
        //         $("#visa").hide();
        //     } else {
        //         $("#mpesa").hide();
        //         $("#visa").hide();
        //     }
        // });
    });

    $('#customer_id').on('change', function() {
        console.log('changed');
        //get the customer id
        var customer_id = $(this).val();
        console.log(customer_id);
        //if customer id is not empty
        if (customer_id != '') {
          //get the customer details
          $.ajax({
            //pass the customer id to the route
            url: "{{ route('transaction.customer_data') }}?customer_id=" + customer_id,
            type: "GET",
            data: {
              customer_id: customer_id,

            },
            dataType:'json',
            // admin.loan.getCustomerDetails
            success: function(data) {
              $('input[name="total_amount"]').val(data.data.total_payable);
              $('input[name="phone"]').val(data.data.customer.phone);
              $('input[name="loan_id"]').val(data.data.loan_id);
            }
          });
        }
      });

</script>

@endpush


@endsection


