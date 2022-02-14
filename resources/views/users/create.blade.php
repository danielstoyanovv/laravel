@extends('layouts.app')

@section('content')
<div>
    <p><h2>{{ __('Create New User') }}</h2></p>
    <p><a href="{{ route('users.index') }}"> {{ __('Back') }}</a></p>
</div>

@if (count($errors) > 0)
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
@endif

{!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
<div>
    <strong>{{ __('Name') }}:</strong>
    {!! Form::text('name', null, array('placeholder' => 'Name', 'class' => 'form-control')) !!}
</div>
<div>
    <strong>{{ __('Email') }}:</strong>
    {!! Form::text('email', null, array('placeholder' => 'Email', 'class' => 'form-control')) !!}
</div>
<div>
            <strong>{{ __('Password') }}:</strong>
            {!! Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control')) !!}
</div>
<div>
    <strong>{{ __('Confirm password') }}:</strong>
        {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
</div>
<div>
    <strong>{{ __('Role') }}:</strong>
    {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
</div>
    
<button type="submit">{{ __('Create') }}</button>
</div>
{!! Form::close() !!}
@endsection