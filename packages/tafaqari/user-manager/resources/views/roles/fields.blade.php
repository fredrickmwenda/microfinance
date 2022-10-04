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

<div class="row">
    <div class="col-sm-12">
        <div class="form-group form-group-default">
            <h5><b>Assign Permissions</b></h5>
            @foreach ($permissions as $permission)
                {{ Form::checkbox('permissions[]',  $permission->id ) }}
                {{ Form::label($permission->name, ucfirst($permission->name)) }}<br>

            @endforeach
        </div>
    </div>
</div>

