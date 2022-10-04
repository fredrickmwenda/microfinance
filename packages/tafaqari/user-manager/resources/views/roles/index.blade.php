@extends('layouts.app')

@section('subheading')

    <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Heading-->
            <div class="d-flex flex-column">
                <!--begin::Title-->
                <h2 class="text-white font-weight-bold my-2 mr-5">{!! Str::plural(Meta::get('title')) !!}</h2>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <div class="d-flex align-items-center font-weight-bold my-2">
                    <!--begin::Item-->
                    <a href="#" class="opacity-75 hover-opacity-100">
                        <i class="flaticon2-shelter text-white icon-1x"></i>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                    <a href="{{ url('/') }}" class="text-white text-hover-white opacity-75 hover-opacity-100">Dashboard</a>
                    <!--end::Item-->

                    <!--begin::Item-->
                    <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                    <span class="text-white text-hover-white opacity-50">Roles</span>
                    <!--end::Item-->
                </div>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Heading-->
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <!--begin::Button-->
            <small class="float-right">
                <button id="show-modal" data-toggle="modal" data-target="#addNewModal" class="btn btn-transparent-white font-weight-bold py-3 px-6 mr-2"><i class="fa fa-plus"></i> Add New </button>
            </small>
            <!--end::Button-->

        </div>
        <!--end::Toolbar-->
    </div>

@endsection

@section('content')

    <!-- MODAL STICK UP  -->
    <div class="modal fade stick-up" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModal" aria-hidden="true">
        <div class="modal-dialog">
            {!! Form::open(['route' => 'roles.store']) !!}
            <div class="modal-content">

                <div class="modal-header clearfix ">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                    </button>
                    <h4 class="p-b-5"><span class="semi-bold">New</span> {{ ucfirst(Str::singular(__('user-manager::messages.users'))) }}</h4>
                </div>
                <div class="modal-body">
                    <p class="small-text">Add a new {{Str::singular( __('messages.roles') ) }}</p>

                    <div class="form-group">
                        {{ Form::label('name', 'Name') }}
                        {{ Form::text('name', null, array('class' => 'form-control')) }}
                    </div>

                    <h5><b>Assign Permissions</b></h5>

                    <div class='form-group'>
                        @foreach ($permissions as $permission)
                            {{ Form::checkbox('permissions[]',  $permission->id ) }}
                            {{ Form::label($permission->name, ucfirst($permission->name)) }}<br>

                        @endforeach
                    </div>


                </div>
                <div class="modal-footer">
                    <button id="add-app" type="submit" class="btn btn-primary  btn-cons">Add</button>
                    <button type="button" class="btn btn-cons" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- End Modal -->

    <div class="card card-custom gutter-b ">
        <div class="card-header">
            <div class="card-title">
                <h5 class="font-14">List of Roles</h5>
            </div>
        </div>
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
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id] ]) !!}
                                    <a data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('roles.edit', $role->id) }}" class="btn btn-info btn-sm pull-left mr-1"><i class="fa fa-pencil-square-o"></i></a>
                                    {!! Form::button('<i class="fa fa-trash"></i>', [
                                       'class' => 'btn btn-sm btn-danger',
                                       'data-toggle' => 'tooltip',
                                       'data-placement' => 'top',
                                       'title' => 'Delete',
                                        'type' => 'submit'
                                       ])
                                    !!}
                                    {!! Form::close() !!}

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
@endsection

@push('pagelevelscripts')
@endpush