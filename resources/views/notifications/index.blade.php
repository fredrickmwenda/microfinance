@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection', ['title' => 'Notifications'])
@endsection

@section('content')
<!--display all notifications, in columns cards that occupy 12 columns-->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="notification_list">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Message') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notifications as $key => $notification)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td>{{ $notification->title ? $notification->title : 'Loan Payment' }}</td>

                                 @php
                                 <!--  -the data column is dispayed as {"customer_name":null,"amount":300,"loan_id":18,"transaction_date":"2023-01-21","transaction_reference":"RAL8UQH5J6","balance":6000}
                                    -we need to get the loan_id from the data column -->
                                    <!-- so it is an array, so we can access it like an array -->
                                    $loan = App\Models\Loan::find($notification->data['loan_id']);
                                    $customer_name = $loan->customer->first_name . ' ' . $loan->customer->last_name;
                                 @endphp
                                <td>{{ $customer_name }} paid {{ $notification->data->amount }} on {{ $notification->data->transaction_date }}</td>
                                <td>{{ $notification->created_at->diffForHumans() }}</td>
                                <td>
                                    <!-- <a href="{{ route('admin.notifications.show', $notification->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> {{ __('View') }}</a>
                                    <button class="btn btn-danger btn-sm" type="button" onclick="deleteData({{ $notification->id }})">
                                        <i class="fas fa-trash"></i> {{ __('Delete') }}
                                    </button> -->
                                    <!-- {"customer_name":null,"amount":300,"loan_id":18,"transaction_date":"2023-01-21","transaction_reference":"RAL8UQH5J6","balance":6000} -->
                                </td>
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


