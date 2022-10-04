@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Withdraw View'])
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
                    <td>{{ __('Withdraw Method') }}</td>
                    <td>{{ $withdraw->withdrawmethod->title }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('User Name') }}</td>
                    <td>{{ $withdraw->user->name }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Account Number') }}</td>
                    <td>{{ $withdraw->user->account_number }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Currency') }}</td>
                    <td>{{ $withdraw->term->title }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Currency Rate') }}</td>
                    <td>{{ $withdraw->term->slug }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Amount') }}</td>
                    <td>{{ $withdraw->amount_usd }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Fee') }}</td>
                    <td>{{ $withdraw->fee }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Total Amount (USD)') }}</td>
                    <td>{{ $withdraw->amount_withdraw / (float) $withdraw->term->slug }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Total Amount') }} ({{ $withdraw->term->title }}) </td>
                    <td>{{ $withdraw->amount_withdraw }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Account') }}</td>
                    <td>{{ $withdraw->account }}</td>
                  </tr>
                  <tr>
                    <td>{{ __('Status') }}</td>
                    <td>
                    @if ($withdraw->status == 1)
                        <span class="badge badge-success">{{ __('Approved') }}</span>
                    @elseif($withdraw->status == 0)
                        <span class="badge badge-danger">{{ __('Rejected') }}</span>
                    @else
                        <span class="badge badge-danger">{{ __('Pending') }}</span>
                    @endif
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