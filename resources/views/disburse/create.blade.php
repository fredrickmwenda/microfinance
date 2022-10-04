@extends('layouts.backend.app')



@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h4>{{ __('Disburse Loan to Customer ') }}</h4>
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
            <form method="POST" action="" class="basicform_with_reset">
              @csrf
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>{{ __('Customer Name') }}</label>
                    <!--select2-->
                    <select class="form-control select2" name="customer_details_id" id="customer_details_id">
                      <option value="">{{ __('Select Customer') }}</option>
                      @foreach ($customer as $customer)
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
                <div class="form-row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Loan Amount') }}</label>
                            <input type="text" class="form-control" name="loan_amounted" placeholder="Loan Amount" required readonly>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                           <!--customer phone number-->
                            <label>{{ __('Loan Interest') }}</label>
                            <input type="text" class="form-control" name="loan_interested"  placeholder="Loan Interest" required readonly>
                        </div>
                    </div>
                  </div>

                  <!--hidden field with loan id-->
                  <input type="hidden" name="loan_id" value="">

               
     

                <div class="form-row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group ">
                        <label for="payment_method">{{ __('Payment Method') }}</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="">{{ __('Select Payment Method') }}</option>
                            <!--option is mpesa, visa, mastercard, paypal, etc-->
                            <option value="mpesa">{{ __('Mpesa') }}</option>
                            <option value="visa">{{ __('Visa') }}</option>
                            <option value="mastercard">{{ __('Mastercard') }}</option>
                            <option value="paypal">{{ __('Paypal') }}</option>
                        </select>
                    </div>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group ">
                        <label for="disburse_amount">{{ __('Disburse Amount') }}</label>
                        <input type="text" class="form-control" id="disburse_amount" name="amount" value="" required>
                    </div>
                  </div>
                         
                </div>

                <div class="form-group">
                  <label for="description">{{ __('Description') }}</label>
                  <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
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

  <script>
    $(document).ready(function() {
      $('#payment_method').select2();
    });
      //on change of select with the name customer_id
      $('#customer_details_id').on('change', function() {
        console.log('changed');
        //get the customer id
        var customer_id = $(this).val();
        console.log(customer_id);
        //if customer id is not empty
        if (customer_id != '') {
          //get the customer details
          $.ajax({
            //pass the customer id to the route
            url: "{{ route('admin.customer_details.get_customer_details') }}?customer_id=" + customer_id,
            type: "GET",
            data: {
              customer_id: customer_id,

            },
            dataType:'json',
            // admin.loan.getCustomerDetails
            success: function(data) {
              $('input[name="loan_amounted"]').val(data.data.loan_amount);
              $('input[name="loan_interested"]').val(data. data.loan_interest);
              $('input[name="phone"]').val(data.data.customer.phone);
              $('input[name="loan_id"]').val(data.data.loan_id);
            }
          });
        }
      });
      


    
  </script>
@endpush

@endsection