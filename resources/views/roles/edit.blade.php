@extends('layouts.app')
@section('content')

<h2>Edit Role</h2>
<a href="{{ route('roles.index') }}"> {{ __('Back') }}</a>

@if (count($errors) > 0)
    <ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    </ul>
@endif

{!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
<div>
    <strong>{{ __('Name') }}:</strong>
    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
</div>
<div>
    <strong>{{ __('Permission') }}:</strong>
    <br/>
    @foreach($permission as $value)
        <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
        {{ $value->name }}</label>
    <br/>
    @endforeach
</div>
<button type="submit">{{ __('Edit') }}</button>
 
{!! Form::close() !!}
@endsection