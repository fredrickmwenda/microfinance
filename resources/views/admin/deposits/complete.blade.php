@extends('layouts.backend.app')

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                    <h4>{{ __('All Deposits Lists - Complete') }}</h4>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                     
                      <th>{{ __('Trx') }}</th>
                      <th>{{ __('User') }}</th>
                      <th>{{ __('Payment Gateway') }}</th>
                      <th>{{ __('Amount') }}</th>
                      <th>{{ __('Charge') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($deposits as $deposit)
                    <tr>
                     
                      <td>{{ $deposit->trx }}</td>
                      <td class="align-middle"> {{ $deposit->user->name }}</td>
                      <td>{{ $deposit->getway->name }}</td>
                      <td>{{ $deposit->amount }}</td>
                      <td>{{ $deposit->charge }}</td>
                    </tr>
                    @endforeach
                  </tbody>
              </table>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection


