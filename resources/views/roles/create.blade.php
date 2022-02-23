@extends('layouts.app')
@section('content')
<h2>{{ __('Create New Role') }}</h2>
<p><a href="{{ route('roles.index') }}"> {{ __('Back') }}</a></p>
   
@if (count($errors) > 0)
    <ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    </ul>
@endif

{!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
<div>
    <strong>{{ __('Name') }}:</strong>
    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
</div>
<div>
    <strong>{{ __('Permission') }}:</strong>
    <br/>
    @foreach($permission as $value)
        <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
        {{ $value->name }}</label>
    <br/>
    @endforeach
</div>
<button type="submit">{{ __('Create') }}</button>
{!! Form::close() !!}
@endsection