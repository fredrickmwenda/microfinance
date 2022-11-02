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
                                <td>{{ $notification->title }}</td>
                                <td>{{ $notification->message }}</td>
                                <td>{{ $notification->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('admin.notifications.show', $notification->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> {{ __('View') }}</a>
                                    <button class="btn btn-danger btn-sm" type="button" onclick="deleteData({{ $notification->id }})">
                                        <i class="fas fa-trash"></i> {{ __('Delete') }}
                                    </button>
                                    <form id="delete-form-{{ $notification->id }}" action="{{ route('admin.notification.destroy', $notification->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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


