@extends('layouts.app')
<!-- START CONTAINER FLUID -->
@section('content')
    <div class="bg-white">
        <div class="container">
            <ol class="breadcrumb breadcrumb-alt">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active text-capitalize">{{ ucfirst(Str::plural(__('user-manager::messages.roles'))) }}</li>
            </ol>
        </div>
    </div>
    <!-- START CONTAINER FLUID -->
    <div class=" container  container-fixed-lg">
        <!-- START card -->
        <div class="card card-transparent">
            <div class="card-header ">
                <div class="card-title">Edit {{ ucfirst(Str::singular(__('user-manager::messages.roles'))) }}
                    <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary float-right">
                        <i class="fa fa-arrow-left"></i>
                        Back To {{ ucfirst(Str::plural(__('user-manager::messages.users'))) }} List
                    </a>
                </div>
                <div class="pull-right">
                    <div class="col-xs-12">

                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                {{ Form::model($role, array('route' => array('roles.update', $role->id), 'method' => 'PUT')) }}

                <div class="form-group">
                    {{ Form::label('name', 'Role Name') }}
                    {{ Form::text('name', null, array('class' => 'form-control')) }}
                </div>

                <h5><b>Assign Permissions</b></h5>
                @foreach ($permissions as $permission)

                    {{Form::checkbox('permissions[]',  $permission->id, $role->permissions ) }}
                    {{Form::label($permission->name, ucfirst($permission->name)) }}<br>

                @endforeach
                <br>
                {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

                {{ Form::close() }}
            </div>
        </div>
        <!-- END card -->
    </div>
    <!-- END CONTAINER FLUID -->
@endsection

