@extends('layouts.app')
@section('content')
<div>
    <p><h2>{{ __('Show user') }}</h2></p>
    <p><a href="{{ route('users.index') }}"> {{ __('Back') }}</a></p>
</div>

<div>
    <strong>{{ __('Name') }}:</strong>
    {{ $user->name }}
</div>
<div>
    <strong>{{ __('Email') }}:</strong>
    {{ $user->email }}
</div>
<div>
    <strong>{{ __('Roles') }}:</strong>
    @if($user->getRoleNames() && !empty($user->getRoleNames()))
        @foreach($user->getRoleNames() as $v)
            <label>{{ $v }}</label>
        @endforeach
    @endif
</div>

@endsection