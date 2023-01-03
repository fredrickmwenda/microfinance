@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Transaction View'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-6">
                    <h4>{{ __('Transaction {{ $transaction->transaction_code }}') }}</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    @if(!empty(Auth::user()->avatar))
                                    <img src="{{ asset('assets/images/profile/'.Auth::user()->avatar) }}" class="rounded-circle" width="150"  alt="" />                 
                                    @else
                                    <img src="{{ asset('assets/backend/admin/assets/img/avatar/avatar-1.png') }}" class="rounded-circle" width="150" alt="">
                                    @endif
                                    <h4 class="card-title mt-10">Transaction was made by: {{ $transaction->customer_name }}</h4>
                                    <p class="card-subtitle"> on {{ $transaction->created_at }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">                                         
                            <div class="card-body">
                                <!--divide the table into two columns-->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td>Customer Name</td>
                                                        <td class="text-right">{{ $transaction->customer_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Transaction No</td>
                                                        <td class="text-right">{{ $transaction->transaction_code }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td> Transaction Amount</td>
                                                        <td class="text-right">Ksh {{ $transaction->amount }}</td>
                                                    </tr>
                                                    <!--transaction reference, transaction date, transaction status, transaction type-->
                                                    <tr>
                                                        <td>Transaction Reference</td>
                                                        <td class="text-right">{{ $transaction->transaction_reference }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Transaction Date</td>
                                                        <td class="text-right">{{ $transaction->transaction_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Transaction Type</td>
                                                        <td class="text-right">{{ $transaction->transaction_type }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Transaction Status</td>
                                                        <td class="text-right text-success">{{ $transaction->transaction_status }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td>Customer ID No</td>
                                                        <td class="text-right">{{ $transaction->customer->national_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Customer Phone Number</td>
                                                        <td class="text-right">{{ $transaction->customer->phone_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Loan Requested Amount</td>
                                                        <td class="text-right text-success">Ksh {{ $transaction->loan->amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Loan Payable Amount</td>
                                                        <td class="text-right">Ksh {{ $transaction->loan->total_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Loan Interest</td>
                                                        <td class="text-right">Ksh {{ $transaction->loan->interest }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Loan Duration</td>
                                                        <td class="text-right">{{ $transaction->loan->duration }} Days</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Loan Status</td>
                                                        <td class="text-right text-success">{{ $transaction->loan->status }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Loan Remaining Balance</td>
                                                        <td class="text-right text-success">Ksh {{ $transaction->loan->remaining_balance }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

