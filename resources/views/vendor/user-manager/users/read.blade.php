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
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary float-right"><i class="fa fa-arrow-left"></i> Back To Users List</a>
                </div>

                <div class="clearfix"></div>
            </div>
            {{-- Form model binding to automatically populate our fields with user data --}}
            <div class="row card-body">

                <div class="col-sm-6 ">
                    <!-- START PANEL -->



                            <div class="form-group">
                                {{ Form::label('name', 'Name') }}
                                <input type="text" class="form-control" value="{{$user->name}}">
                            </div>

                            <div class="form- mt-3">
                                {{ Form::label('email', 'Email') }}
                                <input type="text" class="form-control" value="{{$user->email}}">
                            </div>
                        </div>

            </div>

         
        </div>
        <!-- END card -->
    </div>
    <!-- END CONTAINER FLUID -->
@endsection

