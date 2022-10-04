@extends('layouts.app')

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
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
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
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <a data-toggle="tooltip" data-placement="top" title="View" href="{{ route('start.impersonation', $value->id) }}" class="btn btn-info btn-sm pull-left mr-1">
                                        <i class="fa fa-user-alt"></i>
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
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
