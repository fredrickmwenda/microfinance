@extends('layouts.app')

@section('content')

    <!-- MODAL STICK UP  -->
    <div class="modal fade stick-up" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModal" aria-hidden="true">
        <div class="modal-dialog">
            {{ Form::open(array('route' => 'permissions.store')) }}
            <div class="modal-content">

                <div class="modal-header clearfix ">
                    <h4 class="p-b-5"><span class="semi-bold">New</span> {{ ucfirst(Str::singular(__('user-manager::messages.permissions'))) }}</h4>
                </div>
                <div class="modal-body">
                    <p class="small-text">Add a new {{ ucfirst(Str::singular(__('user-manager::messages.permissions'))) }}</p>
                    <div class="form-group">
                        {{ Form::label('name', 'Permission Name') }}
                        {{ Form::text('name', '', array('class' => 'form-control')) }}
                    </div><br>
                    @if(!$roles->isEmpty())
                        <h4 class="mt-4 mb-4">Assign Permission to Roles</h4>

                        @foreach ($roles as $role)
                            {{ Form::checkbox('roles[]',  $role->id ) }}
                            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

                        @endforeach
                    @endif
                    <br>




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
                        List Of Permissions
                    </h4>

                    <div class="page-title-right">
                        <button type="button" class="btn btn-outline-danger float-end" data-bs-toggle="modal" data-bs-target="#addNewModal">
                            New Permission
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="card card-custom gutter-b ">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed table-hover">
                            <thead>
                            <tr>
                                <th>Permissions</th>
                                <th>Operation</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($permissions)
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td><p>{{ $permission->name }}</p></td>
                                        <td>
                                            <!--{!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ]) !!}-->
                                            <a  data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;"><i class="fa fa-edit"></i> </a>


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

                            @else
                                <tr><td colspan="4">No data available</td></tr>
                            @endif

                            </tbody>
                        </table>
                        {{ $permissions->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('pagelevelscripts')
@endpush
