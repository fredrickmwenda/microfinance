@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Deposit'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
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
                            <td>{{ __('Payment Id') }}</td>
                            <td>{{ $info->trx }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Amount (USD)') }}</td>
                            <td>{{ $info->amount }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Getway') }}</td>
                            <td>{{ $info->getway->name }}</td>
                        </tr>
                        
                         <tr>
                            <td>{{ __('Charge') }}</td>
                            <td>{{ $info->charge }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Status') }}</td>
                            <td>{{ $info->status }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Requested At') }}</td>
                            <td>{{ $info->created_at->format('d-F-Y') }} ({{ $info->created_at->diffForHumans() }})</td>
                        </tr>
                        @php
                        $meta=json_decode($info->meta->value);
                        @endphp
                        <hr>
                        <tr>
                            <td>{{ __('Currency') }}</td>
                            <td>{{ $meta->currency }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Currency Rate') }}</td>
                            <td>{{ $meta->rate }}</td>
                        </tr>
                        <tr>
                        	<td>{{ __('Amount') }}</td>
                        	<td>{{ $meta->amount }} ({{ $meta->currency }})</td>
                        </tr>
                        <tr>
                        	<td>{{ __('Comment') }}</td>
                        	<td>{{ $meta->comment }} </td>
                        </tr>
                        <tr>
                        	<td>{{ __('Attachment') }}</td>
                        	<td><a href="{{ asset($meta->image) }}" target="_blank"><img src="{{ asset($meta->image) }}" height="50"></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection