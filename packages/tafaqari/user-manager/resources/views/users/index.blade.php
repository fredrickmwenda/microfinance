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
                    <span class="text-white text-hover-white opacity-50">Users</span>
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
            {!! Form::open(['route' => 'users.store']) !!}
            <div class="modal-content">

                <div class="modal-header clearfix ">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                    </button>
                    <h4 class="p-b-5"><span class="semi-bold">New</span> {{ ucfirst(Str::singular(__('user-manager::messages.users'))) }}</h4>
                </div>
                <div class="modal-body">
                    <p class="small-text">Add a new {{ ucfirst(Str::singular(__('user-manager::messages.users'))) }}</p>

                    <div class="form-group">
                        {{ Form::label('name', 'Name') }}
                        {{ Form::text('name', '', array('class' => 'form-control')) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('email', 'Email') }}
                        {{ Form::email('email', '', array('class' => 'form-control')) }}
                    </div>

                    <div class='form-group'>
                        @foreach ($roles as $role)
                            {{ Form::checkbox('roles[]',  $role->id ) }}
                            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

                        @endforeach
                    </div>

                    <div class="form-group">
                        {{ Form::label('password', 'Password') }}<br>
                        {{ Form::password('password', array('class' => 'form-control')) }}

                    </div>

                    <div class="form-group">
                        {{ Form::label('password', 'Confirm Password') }}<br>
                        {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

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
                <h5 class="font-14">List of Users</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Email</th>
                        <th>Permissions</th>
                        <th>Operations</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!$users->isEmpty())
                        @foreach($users as $key=>$value)
                            <tr>
                                <td class="v-align-middle">
                                    <p>{{ $value->name }}</p>
                                </td>
                                <td class="v-align-middle">
                                    <p>{{ $value->type }}</p>
                                </td>
                                <td class="v-align-middle">
                                    <p>{{ $value->email }}</p>
                                </td>

                                <td>
                                    <p>{{  ucfirst($value->roles()->pluck('name')->implode(', ')) }}</p>
                                </td>
                                <td>


                                    {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $value->id] ]) !!}
                                    <a data-toggle="tooltip" data-placement="top" title="View" href="{{ route('users.edit', $value->id) }}" class="btn btn-info btn-sm pull-left mr-1">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
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

                    @else
                        <tr><td colspan="4">No data available</td></tr>
                    @endif

                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>

    </div>
@endsection

@push('pagelevelscripts')
@endpush
