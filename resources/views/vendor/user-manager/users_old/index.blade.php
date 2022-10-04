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
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                        List Of Users
                    </h4>

                    <div class="page-title-right">
                        <button type="button" class="btn btn-outline-danger float-end" data-bs-toggle="modal"
                            data-bs-target="#addNewModal">
                            New User
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card card-custom gutter-b ">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Id Number</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Operations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$users->isEmpty())
                                        @foreach ($users as $key => $value)
                                            <tr>
                                                <td class="v-align-middle">
                                                    <p>{{ $value->name }}</p>
                                                </td>


                                                <td class="v-align-middle">
                                                    <p>{{ $value->email }}</p>
                                                </td>
                                                <td class="v-align-middle">
                                                    <p>{{ $value->email }}</p>
                                                </td>

                                                <td>
                                                    <div class="row">
                                                        <p>{{ $value->roles()->pluck('name')->implode(', ')? ucfirst($value->roles()->pluck('name')->implode(', ')): 'None' }}
                                                        </p>

                                                        <a href="{{ route('roles.users.show', $value->id) }}">More
                                                            Details</a>
                                                    </div>

                                                </td>

                                                <td>
                                                    {{ $value->status ? ucfirst($value->status) : 'Not Set' }}
                                                </td>

                                                <td>
                                                    {{ !is_null($value->group) ? $value->group->name : 'Not Set' }}
                                                </td>



                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">No data available</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                            {{ $users->links() }}
                        </div>
                    </div>

                </div>
            </div>
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
