@extends('layouts.app')
<!-- START CONTAINER FLUID -->
@section('content')
    @include('layouts.masthead')

        <div class="bg-white">
            <div class="container">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active text-capitalize">{{ ucfirst(Str::plural(__('user-manager::messages.users'))) }}</li>
                </ol>
            </div>
        </div>

    <!-- START CONTAINER FLUID -->
    <div class=" container  container-fixed-lg col-lg-10">

        <!-- START card -->
        <div class="card card-transparent">
            <div class="card-header ">
                <div class="card-title">
                    Edit {{ ucfirst(Str::singular(__('user-manager::messages.users'))) }}
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary float-right"><i class="fa fa-arrow-left"></i> Back To Users List</a>
                </div>

                <div class="clearfix"></div>
            </div>
            <div class="row card-body">
                <div class="col-sm-6 ">
                    <!-- START PANEL -->

                            {{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}
                            {{-- Form model binding to automatically populate our fields with user data --}}

                            <div class="form-group">
                                {{ Form::label('name', 'Name') }}
                                {{ Form::text('name', null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('email', 'Email') }}
                                {{ Form::email('email', null, array(
                                    'class' => 'form-control',
                                    'autocomplete' => 'off',
                                    'readonly' => 'readonly'
                                    ))
                                }}
                            </div>

                            <h6><b>Give Role</b></h6>

                            <div class='form-group'>
                                @foreach ($roles as $role)
                                    {{ Form::checkbox('roles[]',  $role->id, $user->roles ) }}
                                    {{ Form::label($role->name, ucfirst($role->name)) }}<br>

                                @endforeach
                            </div>

                            {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}

                            {{ Form::close() }}
                        </div>

                    <!-- END PANEL -->

                </div>
            </div>
        </div>
        <!-- END card -->
    </div>
    <!-- END CONTAINER FLUID -->
@endsection

