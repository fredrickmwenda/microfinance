@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Transaction View'])
@endsection

@section('content')
<div class="row">
    <div class="col-8">
      <div class="card">
        <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Info') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ __('User Name') }}</td>
                    <td>{{ $transaction->user->name }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Account Number') }}</td>
                    <td>{{ $transaction->user->account_number }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Amount') }}</td>
                    <td>{{ $transaction->user->amount }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Balance') }}</td>
                    <td>{{ $transaction->user->balance }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Trx Id') }}</td>
                    <td>{{ $transaction->trxid }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Transaction Amount') }}</td>
                    <td>{{ $transaction->amount }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Fee') }}</td>
                    <td>{{ $transaction->fee }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Info') }}</td>
                    <td>{{ $transaction->info }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Type') }}</td>
                    <td>{{ $transaction->type }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Status') }}</td>
                    <td>
                      <span class="badge badge-primary">{{ ($transaction->status == 1) ? 'success' : 'pending' }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
      </div>
  </div>
</div>
@endsection