@extends('layouts.app')
<!-- START CONTAINER FLUID -->
@section('content')
    <!-- START CONTAINER FLUID -->
    <div class=" container  container-fixed-lg col-lg-10">

          <!-- START card -->
          <div class="card card-transparent">
            <div class="card-header ">
                <div class="card-title">
                    Edit {{ ucfirst(Str::singular(__('user-manager::messages.users'))) }}
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary float-right"><i
                            class="fa fa-arrow-left"></i> Back To Users List</a>
                </div>

                <div class="clearfix"></div>
            </div>
            {{ Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PUT']) }}
            {{-- Form model binding to automatically populate our fields with user data --}}
            <div class="row card-body">

                <div class="col-sm-6 ">
                    <!-- START PANEL -->
                    <div class="form-group">
                        {{ Form::label('name', 'Name') }}
                        {{ Form::text('name', null, [
                        'class' => 'form-control',
                        'readonly' => true,
                        ]) }}
                    </div>

                    <div class="form- mt-3">
                        {{ Form::label('email', 'Email') }}
                        {{ Form::email('email', null, [
                            'class' => 'form-control',
                            'autocomplete' => 'off',
                            'readonly' => true,
                        ]) }}
                    </div>

                    {{-- <h6 class="mt-3 mb-3"><b>Give Role</b></h6>

                    <div class='form-group ml-4 p-2'>
                        @foreach ($roles as $role)
                            {{ Form::checkbox('roles[]', $role->id, $user->roles) }}
                            {{ Form::label($role->name, ucfirst($role->name)) }}<br>
                        @endforeach
                    </div> --}}

                    <div class="form-group mb-3">
                        <label class="col-form-label">Update User Status</label>

                        {!! Form::select('status', ['active' => "Active", 'disabled' => "Disabled", 'permanently disabled'=> "Permanently Disabled"], null, [
                            'class' => 'form-control select-2',
                            'required'=> true,
                            'data-msg-required' => 'Please pick a status',
                            'placeholder' => 'Pick one',
                        ]) !!}

                    </div>

                    <div class="form-group">
                        <label for="phone" class="col-md-4 col-form-label text-md-right">Phone Number</label>

                        <div class="col-md-12">
                            <input id="phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="phone_number" value="{{ $user->phone_number }}" autocomplete="off"
                                autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class='form-group mt-2 mr-3 bg-gradient-light p-4'>
                        @php $user_groups = (\App\Models\UserGroup::where('status', 'active'))->get(); @endphp
                        <label for="">User Group(s)</label>
                        
                        @if (isset($user_groups))
                            <div>
                                @foreach ($user_groups as $user_group)

                                    {{ Form::checkbox('user_groups[]', $user_group->id, in_array($user_group->id, $pivots)) }}
                                    {{ Form::label($user_group->name, ucfirst($user_group->name)) }}<br>

                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>

                <!-- END PANEL -->
                <div class="col-lg-6">

                    
                    <div class="form-group row m-2">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Personal Number</label>

                        <div class="col-md-6">
                            <input readonly id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="personal_number" value="{{ $user->personal_number }}" required autocomplete="name"
                                autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

              
                    <div class="form-group row m-2">
                        <label for="department_id" class="col-md-4 col-form-label text-md-right">Department</label>
                        <div class="col-md-6">
                            {{ Form::text('department', null, ['class' => 'form-control',

                                'readonly' => true

                            ]) }}
                        </div>
                    </div>



                    <div class="form-group row m-2">
                        <label for="division_id" class="col-md-4 col-form-label text-md-right">Division</label>
                        <div class="col-md-6">
                            {{ Form::text('division', null, ['class' => 'form-control','readonly' => true]) }}
                        </div>
                    </div>

                    <div class="form-group row m-2">
                        <label for="station_id" class="col-md-4 col-form-label text-md-right">Station</label>
                        <div class="col-md-6">
                            @php $stations = (\App\Models\Station::pluck('name', 'id')) @endphp
                            @if (isset($stations))
                                {!! Form::select('station_id', $stations, null, [
    'class' => 'form-control select-2',
    'data-msg-required' => 'Please pick a station',
    'placeholder' => 'Pick one',
    // 'required' => true,
]) !!}
                            @endif
                        </div>
                    </div>

                    <div class="form-group row m-2">
                        <label for="position_id" class="col-md-4 col-form-label text-md-right">Job Title</label>
                        <div class="col-md-6">
                            {{ Form::text('position', null, ['class' => 'form-control', 'readonly' => true]) }}
                        </div>
                    </div>

                    <div class="form-group row m-2">
                        <label for="supervisor_id" class="col-md-4 col-form-label text-md-right">Supervisor</label>
                        <div class="col-md-6">
                            
                            @php $supervisors = (\App\Models\User::pluck('name', 'id')) @endphp
                            @if (isset($supervisors))
                                {!! Form::select('supervisor_id', $supervisors, null, [
    'class' => 'form-control select-2',
    'data-msg-required' => 'Please pick a supervisor',
    'placeholder' => 'Pick one',
    // 'required' => true,
]) !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}

                    </div>

                </div>
            </div>

            {{ Form::close() }}
        </div>
    </div>
    <!-- END CONTAINER FLUID -->
@endsection
