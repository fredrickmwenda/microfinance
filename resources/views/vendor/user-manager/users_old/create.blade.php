@extends('layouts.app')

@section('content')

    <!-- MODAL STICK UP  -->
    <div class="modal fade stick-up" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModal"
        aria-hidden="true">
        <div class="modal-dialog">
            {!! Form::open(['route' => 'users.store']) !!}
            <div class="modal-content">

                <div class="modal-header clearfix ">
                    <h4 class="p-b-5"><span class="semi-bold">New</span>
                        {{ ucfirst(Str::singular(__('user-manager::messages.users'))) }}</h4>
                </div>
                <div class="modal-body">
                    <p class="small-text">Add a new {{ ucfirst(Str::singular(__('user-manager::messages.users'))) }}
                    </p>

                    <!-- <div class="form-group">
                        {{ Form::label('name', 'Personal Number') }}
                        {{ Form::text('personal_number', '', [
                            'class' => 'form-control',
                            'id' => 'newUserPersonalNumber',
                            'required' => true,
                            'placeholder' => "Type the user's personal number to autopopulate",
                        ]) }}
                    </div> -->

                    <div class="form-group">
                        {{ Form::label('name', 'FirstName') }}
                        {{ Form::text('first_name', '', ['class' => 'form-control', 'id' => 'firstNameInput', 'readonly' => true, 'required' => true]) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('name', 'LastName') }}
                        {{ Form::text('last_name', '', ['class' => 'form-control', 'id' => 'lastNameInput', 'readonly' => true, 'required' => true]) }}
                    </div>                   

                    <div class="form-group">
                        {{ Form::label('email', 'Email') }}
                        {{ Form::email('email', null, ['class' => 'form-control', 'required' => true, 'readonly' => true, 'id' => 'emailInput']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('email', 'Email') }}
                        {{ Form::email('email', null, ['class' => 'form-control', 'required' => true, 'readonly' => true, 'id' => 'emailInput']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('email', 'Email') }}
                        {{ Form::email('email', null, ['class' => 'form-control', 'required' => true, 'readonly' => true, 'id' => 'emailInput']) }}
                    </div>

                    <div class='form-group mt-2 mr-3 bg-gradient-light p-4'>
                        @foreach ($roles as $role)
                            {{ Form::checkbox('roles[]', $role->id) }}
                            {{ Form::label($role->name, ucfirst($role->name)) }}<br>
                        @endforeach
                    </div>

                    <div class="form-group row m-2">
                        <label for="position_id" class="col-md-4 col-form-label text-md-right">Station</label>
                        <div class="col-md-6">
                            @php $divisions = (\App\Models\Station::pluck('name', 'id')) @endphp
                            @if(isset($divisions))
                                {!! Form::select('station_id', $divisions, null, [
                                        'class' => 'form-control select-2',
                                        'required' => true,
                                        'data-msg-required'=>'Please pick a category',
                                        'placeholder'=>'Pick one',
                                        ])
                                 !!}
                            @endif
                        </div>
                    </div>

                    <div class="form-group row m-2">
                        <label for="supervisor_id" class="col-md-4 col-form-label text-md-right">Choose Supervisor</label>
                        <div class="col-md-6">
                            @php $userss = (\App\User::where('status', 'active')->pluck('name', 'id')) @endphp
                            @if(isset($userss))
                                {!! Form::select('supervisor_id', $userss, null, [
                                        'class' => 'form-control select-2',
                                        'required' => true,
                                        'data-msg-required'=>'Please pick a category',
                                        'placeholder'=>'Pick one',
                                        ])
                                 !!}
                            @endif
                        </div>
                    </div>

                    <div class="form-group row m-2">
                        <label for="position_id" class="col-md-4 col-form-label text-md-right">User Group</label>
                        <div class="col-md-6">
                            @php $user_groups = (\App\Models\UserGroup::where('status', 'active')->pluck('name', 'id')) @endphp
                            @if (isset($user_groups))
                                {!! Form::select('user_group_id', $user_groups, null, [
                                    'class' => 'form-control select-2',
                                    'data-msg-required' => 'Please pick a category',
                                    'required' => true,
                                    'placeholder' => 'Pick one',
                                ]) !!}
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Begin hidden fields --}}

                <input type="hidden" id="department_input" name="department" value="">
    
                {{-- Job title --}}
                <input type="hidden" id="position_input" name="position" value="">
                <input type="hidden" id="division_input" name="division" value="">

                {{-- End hidden fields --}}

                <div class="modal-footer">
                    <button id="add-app" type="submit" class="btn btn-primary  btn-cons">Add</button>
                    <button type="button" class="btn btn-cons" data-bs-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- End Modal -->
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
            {{ Form::model($user, ['route' => ['route' => 'users.store'], 'method' => 'POST']) }}
            
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

                    <!-- <div class="form-group row m-2">
                        <label for="position_id" class="col-md-4 col-form-label text-md-right">Job Title</label>
                        <div class="col-md-6">
                            {{ Form::text('position', null, ['class' => 'form-control', 'readonly' => true]) }}
                        </div>
                    </div> -->

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
@endsection

@push('scripts')
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
