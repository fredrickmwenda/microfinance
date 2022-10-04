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
                        {{ Form::text('first_name', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('name', 'Name') }}
                        {{ Form::text('last_name', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form- mt-3">
                        {{ Form::label('email', 'Email') }}
                        {{ Form::email('email', null, [
                            'class' => 'form-control',
                            'autocomplete' => 'off',
                            'readonly' => 'readonly',
                        ]) }}
                    </div>

                    <h6 class="mt-3 mb-3"><b>Give Role</b></h6>

                    <div class='form-group ml-4 p-2'>
                        @foreach ($roles as $role)
                            {{ Form::checkbox('roles[]', $role->id, $user->roles) }}
                            {{ Form::label($role->name, ucfirst($role->name)) }}<br>
                        @endforeach
                    </div>

                    <div class="form-group mb-3">
                        <label class="col-form-label">Update User Status</label>

                        {!! Form::select('status', ['active' => "Active", 'disabled' => "Disabled", 'permanently disabled'=> "Permanently Disabled"], null, [
                            'class' => 'form-control select-2',
                            'required'=> true,
                            'data-msg-required' => 'Please pick a status',
                            'placeholder' => 'Pick one',
                        ]) !!}

                    </div>
                </div>


                <!-- END PANEL -->
                <div class="col-lg-6">
                    <div class="form-group row m-2">
                        <label for="phone" class="col-md-4 col-form-label text-md-right">Phone Number</label>

                        <div class="col-md-6">
                            <input id="phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="phone_number" value="{{ $user->phone }}" autocomplete="off"
                                autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>




                    <!-- <div class="form-group row m-2">
                        <label for="department_id" class="col-md-4 col-form-label text-md-right">User Group</label>
                        <div class="col-md-6">
                            @php $departments = \App\Models\UserGroup::where('status', 'active')->pluck('name', 'id') @endphp
                            @if (isset($departments))
                                {!! Form::select('user_group_id', $departments, null, [
                                'class' => 'form-control select-2',
                                'data-msg-required' => 'Please pick a category',
                                'placeholder' => 'Pick one',
                                'required' => true,
                            ]) !!}
                            @endif
                        </div>
                    </div> -->

                    <!--National identity card-->
                    <div class="form-group row m-2">
                        <label for="division_id" class="col-md-4 col-form-label text-md-right">ID Number</label>
                        <div class="col-md-6">
                            {{ Form::text('national_id', null, ['class' => 'form-control','readonly' => true]) }}
                        </div>
                    </div>
                    <!--branch which the user is located in-->
                    <div class="form-group row m-2">
                        <label for="station_id" class="col-md-4 col-form-label text-md-right">Branch</label>
                        <div class="col-md-6">
                            @php $branches = (\App\Models\Branch::pluck('name', 'id')) @endphp
                            @if (isset($$branches))
                                {!! Form::select('branch_id', $branches, null, [
                                        'class' => 'form-control select-2',
                                        'data-msg-required' => 'Please pick a branch',
                                        'placeholder' => 'Pick one',
                                        // 'required' => true,
                                    ]) 
                                !!}
                            @endif
                        </div>
                    </div>

                    <div class="form-group row m-2">
                        <label for="position_id" class="col-md-4 col-form-label text-md-right">Job Title</label>
                        <div class="col-md-6">
                            {{ Form::text('position', null, ['class' => 'form-control', 'readonly' => true]) }}
                        </div>
                    </div>

                    <!-- <div class="form-group row m-2">
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
                    </div> -->
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
