<?php
/**
 * Created by PhpStorm.
 * User: Jack MN
 * Date: 02/05/2020
 * Time: 07:27 PM
 */
?>
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
@if(!$roles->isEmpty()) //If no roles exist yet
<div class="row">
    <div class="col-sm-12">
        <div class="form-group form-group-default">
            <h4>Assign Permission to Roles</h4>
            @foreach ($roles as $role)
                {{ Form::checkbox('roles[]',  $role->id ) }}
                {{ Form::label($role->name, ucfirst($role->name)) }}<br>

            @endforeach
        </div>
    </div>
</div>
@endif
