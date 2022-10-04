@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <h4>{{__('Loan Request Details')}}</h4>
            </div>
            <div class="p-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Details') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ __('User Name') }}</td>
                            <td>{{ $loan->user->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('User Account No') }}</td>
                            <td>{{ $loan->user->account_number }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Loan Plan') }}</td>
                            <td>{{ $loan->loanplan->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Amount') }}</td>
                            <td>{{ $loan->amount }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Charge') }}</td>
                            <td>{{ $loan->charge }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Return Date') }}</td>
                            <td>{{ date('d-m-Y', strtotime($loan->return_date)) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Status') }}</td>
                            @if($loan->status == 2) 
                                <td class="text-danger font-weight-bold">{{ __('Pending') }}</td>
                            @endif
                            @if($loan->status == 1) 
                                <td class="text-success font-weight-bold">{{ __('Approved') }}</td>
                            @endif
                            @if($loan->status == 0) 
                                <td class="text-danger font-weight-bold">{{ __('Rejected') }}</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection



