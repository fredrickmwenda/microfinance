@extends('layouts.app')
<!-- START CONTAINER FLUID -->
@section('content')
    <div class="bg-white">
        <div class="container">
            <ol class="breadcrumb breadcrumb-alt">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active text-capitalize">{!! Str::plural(Meta::get('title')) !!}</li>
            </ol>
        </div>
    </div>
    <!-- START CONTAINER FLUID -->
    <div class=" container  container-fixed-lg">
        <!-- START card -->
        <div class="card card-transparent">
            <div class="card-header ">
                <div class="card-title">Edit Permissions
                    <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-primary float-right"><i class="fa fa-arrow-left"></i> Back To {!! Str::plural(Meta::get('title')) !!} List</a>
                </div>
                <div class="pull-right">
                    <div class="col-xs-12">

                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div class="col-sm-7">
                    <!-- START PANEL -->
                    {{ Form::model($permission, array('route' => array('permissions.update', $permission->id), 'method' => 'PUT')) }}
                        {!! Form::hidden('id', null, ['class' => 'form-control']) !!}
                         <div class="row">
                             <div class="col-sm-12">
                                 <div class="form-group form-group-default">
                                     <label>Name</label>
                                     {!! Form::text('name', null, [
                                     'class' => 'form-control',
            'autocomplete' => 'off',
                                     'required'=>'required',
                                     'placeholder'=>'Name of your survey eg D6',
                                     'data-rule-required'=>'true',
                                     'data-msg-required'=>'This field is required'
                                     ]) !!}
                                 </div>
                             </div>
                         </div>
                        <button id="add-app" type="submit" class="btn btn-primary  btn-cons">Save</button>

                    {!! Form::close() !!}

                    <!-- END PANEL -->
                </div>
            </div>
        </div>
        <!-- END card -->
    </div>
    <!-- END CONTAINER FLUID -->
@endsection


