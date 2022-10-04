@extends('layouts.backend.app')



@push('css')
    <link href="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <h4>Disburse Loan to {{ $loan->customer->first_name }} {{ $loan->customer->last_name }}</h4>            
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


                <form method="POST" action="{{ route('disburse.loan.store', $loan->loan_id) }}" id="loanDisburser">
                    @csrf
                    <div class="pt-20">
                
                        <div class="form-row">
                        
                            <div class="form-group col-md-6">
                            <label for="customer_name">{{ __('Customer Name') }}</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $loan->customer->first_name }} {{ $loan->customer->last_name }}" readonly>

                            </div>
                            <!--customer phone number from customer table-->
                            <div class="form-group col-md-6">
                            <label for="customer_phone">{{ __('Customer Phone') }}</label>
                            <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="{{ $loan->customer->phone }}" >
                            </div>
                        </div>

                        <!--loan amount from loan table-->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="loan_amount">{{ __('Loan Amount') }}</label>
                            <input type="text" class="form-control" id="loan_amount" name="loan_amount" value="{{ $loan->loan_amount }}" readonly>
                            </div>
                            <!--loan interest from loan table-->
                            <div class="form-group col-md-6">
                            <label for="loan_interest">{{ __('Loan Interest') }}</label>
                            <input type="text" class="form-control" id="loan_interest" name="loan_interest" value="{{ $loan->loan_interest }}" readonly>
                            </div>
                        </div>

                        <!--Payment method from payment gateway table-->
                        <div class="form-row">
                            <div class="form-group col-md-6">
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

                            <!--enter amount to be disbursed-->
                            <div class="form-group col-md-6">
                                <label for="disburse_amount">{{ __('Disburse Amount') }}</label>
                                <input type="text" class="form-control" id="disburse_amount" name="amount" value="{{ $loan->loan_amount }}" required>
                            </div>
                            <!--loan interest from loan table-->
                        </div>

                        <!--whole description row-->
                        <div class="form-group">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                    
                    </div>
                </div>
			</div>
	</div>
    <div class="col-lg-3">
		<div class="single-area">
			<div class="card">
				<div class="card-body">
					<div class="btn-publish">
						<button type="submit" class="btn btn-primary col-12 basicbtn"><i class="fa fa-save"></i> {{ __('Save') }}</button>
					</div>
				</div>
			</div>
		</div>	
	</div>
</form>

@endsection

@push('js')
<script src="{{ asset('Backend/admin/assets/js/select2.min.js') }}"></script>
@endpush

@push('js')
<script  text="text/javascript">
    $(document).ready(function() {
        $('#loanDisburser').on('submit', function(e){
            e.preventDefault();
            //store loan_id and customer_id in local storage
            var loan_id = localStorage.getItem('loan_id');
            var customer_id = localStorage.getItem('customer_id');
        });

    });
</script>

