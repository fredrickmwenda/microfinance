@extends('layouts.app')

@section('content')

    <!-- MODAL STICK UP  -->
    <div class="modal fade stick-up" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModal" aria-hidden="true">
        <div class="modal-dialog">
            {!! Form::open(['route' => 'roles.store']) !!}
            <div class="modal-content">

                <div class="modal-header clearfix ">
                    <h4 class="p-b-5"><span class="semi-bold">New</span> {{ ucfirst(Str::singular(__('user-manager::messages.roles'))) }}</h4>
                </div>
                <div class="modal-body">
                    <p class="small-text">Add a new {{Str::singular( __('user-manager::messages.roles') ) }}</p>

                    <div class="form-group">
                        {{ Form::label('name', 'Name') }}
                        {{ Form::text('name', null, array('class' => 'form-control')) }}
                    </div>

                    <h5 class="mt-4 mb-4"><b>Assign Permissions</b></h5>

                    <div class='form-group'>
                        @foreach ($permissions as $permission)
                            {{ Form::checkbox('permissions[]',  $permission->id ) }}
                            {{ Form::label($permission->name, ucfirst($permission->name)) }}<br>

                        @endforeach
                    </div>


                </div>
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
                        List Of Roles
                    </h4>

                    <div class="page-title-right">
                        <button type="button" class="btn btn-outline-danger float-end" data-bs-toggle="modal" data-bs-target="#addNewModal">
                            New Role
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- start page title -->
        <div class="row">
            <div class="card card-custom gutter-b ">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-condensed table-striped table-responsive-block" >
                    <thead>
                    <tr>
                        <th>Role</th>
                        <th width="60%">Permissions</th>
                        <th>Operation</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!$roles->isEmpty())
                        @foreach ($roles as $role)
                            <tr>

                                <td>{{ ucfirst($role->name) }}</td>

                                <td>{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                                <td>
                                    <!--{!! Form::open(['method' => 'DELETE', 'route' => ['role.destroy', $role->id] ]) !!}-->
                                    <a data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('role.edit', $role->id) }}" class="btn btn-info btn-sm pull-left mr-1"><i class="fa fa-edit"></i></a>
                                    <!--{!! Form::button('<i class="fa fa-trash"></i>', [
                                       'class' => 'btn btn-sm btn-danger',
                                       'data-toggle' => 'tooltip',
                                       'data-placement' => 'top',
                                       'title' => 'Delete',
                                        'type' => 'submit'
                                       ])
                                    !!}
                                    {!! Form::close() !!}-->

                                </td>
                            </tr>
                        @endforeach


                    @endif

                    </tbody>
                </table>
                {{ $roles->links() }}
            </div>
        </div>

    </div>
        </div>
    </div>
@endsection

@push('pagelevelscripts')
@endpush