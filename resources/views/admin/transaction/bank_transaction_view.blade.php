@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Transaction View'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Details') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php
                        $info = json_decode($transaction->info);
                        @endphp
                        <tr>
                            <td>{{ __('User Name') }}</td>
                            <td>{{ $transaction->user->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Transaction Type') }}</td>
                            <td>{{ str_replace('_',' ',$transaction->transaction->type) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Bank Name') }}</td>
                            <td>{{ $transaction->bank->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Account Holder Name') }}</td>
                            <td>{{ $info->account_holder_name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Account Number') }}</td>
                            <td>{{ $info->account_number }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Currency') }}</td>
                            <td>{{ $transaction->term->title }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Currency Rate') }}</td>
                            <td>{{ $transaction->currency_rate }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Amount (USD)') }}</td>
                            <td>{{ $transaction->transaction->amount }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Fee') }}</td>
                            <td>{{ $transaction->transaction->fee }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Final Amount (USD)') }}</td>
                            <td>{{ $transaction->transaction->amount - $transaction->transaction->fee }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Amount') }} ({{ $transaction->term->title }})</td>
                            <td>{{ $transaction->amount }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Branch') }}</td>
                            <td>{{ $info->branch }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Status') }}</td>
                            <td>@if($transaction->status == 1) {{ __('Success') }} @elseif($transaction->status == 2) {{ __('Pending') }}  @else {{ __('Rejected') }} @endif</td>
                        </tr>
                        <tr>
                            <td>{{ __('Transaction Date') }}</td>
                            <td>{{ date('d-m-Y h:i', strtotime($transaction->created_at)) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection