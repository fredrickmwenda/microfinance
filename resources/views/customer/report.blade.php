@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Report'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-6">
                  <h5> {{ ucwords($type) }} {{ __('report for') }} {{ $user->name }}</h5>
                </div>
                <div class="col-lg-6">
                    <div class="add-new-btn">
                        <a href="{{ route('admin.user.transaction.pdf',[$type, $user->id]) }}" class="btn btn-primary float-right">{{ __('PDF') }}</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="table-2">
                  <thead>
                    <tr>
                        <th scope="col">{{ __('Sl') }}.</th>
                        <th scope="col">{{ __('Trx') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th scope="col">{{ __('Balance') }}</th>
                        <th scope="col">{{ __('Fee') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Date') }}</th>
                        <th scope="col">{{ __('Type') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $row->trxid }}</td>
                        <td>{{ $row->amount }}</td>
                        <td>{{ $row->balance }}</td>
                        <td>{{ $row->fee }}</td>
                        @if($row->status == 1)
                        <td class="text-success">{{ __('Active') }}</td>
                        @endif
                        @if($row->status == 0)
                        <td class="text-danger">{{ __('Inactive') }}</td>
                        @endif
                        <td>{{ date('d-m-Y h:i', strtotime($row->created_at)) }}</td>
                        <td>{{ $row->type }}</td>
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