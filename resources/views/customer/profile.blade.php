@extends('layouts.backend.app')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Profile'])
@endsection

@section('content')
<div class="row">
   <div class="col-12">
      <!-- main Section -->
      <div class="row">
        <div class="col-md-8 offset-2">
            <div class="card">
               <div class="card-body">
                  <div class="card profile-widget">
                     <div class="profile-widget-header d-flex ">
                        <img alt="image" src="{{ !empty($user_transactions->image) ? asset($user_transactions->image) : url('https://ui-avatars.com/api/?background=random&name='.$user_transactions->name) }}" class="rounded-circle profile-widget-picture mx-auto">
                     </div>
                  </div>
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Description') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{ __('Name') }}</td>
                        <td>{{ $user_transactions->name }}</td>
                      </tr>
                      <tr>
                        <td>{{ __('Email') }}</td>
                        <td>{{ $user_transactions->email }}</td>
                      </tr>
                      <tr>
                        <td>{{ __('Phone') }}</td>
                        <td>{{ $user_transactions->phone }}</td>
                      </tr>
                      <tr>
                        <td>{{ __('Status') }}</td>
                        <td>
                          @if ($user_transactions->status == 1)
                            <span class='badge badge-success'>{{ __('Active') }}</span>
                          @else 
                            <span class='badge badge-danger'>{{ __('Not Active') }}</span>
                          @endif
                         </td>
                      </tr>
                      <tr>
                        <td colspan=2>
                          <a href="{{ route('admin.profile.edit', $user_transactions->id) }}" class="btn btn-primary btn-block">{{ __('Edit') }}</a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
               </div>
            </div>
        </div>
      </div><!-- End Main Row -->
   </div><!-- End col 12 -->
</div><!-- End row -->
@endsection