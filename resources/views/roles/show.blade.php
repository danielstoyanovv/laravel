@extends('layouts.app')
@section('content')
<h2>{{ __('Show Role') }}</h2>
<a href="{{ route('roles.index') }}"> {{ __('Back') }}</a>

<div>
    <strong>{{ __('Name') }}:</strong>
    {{ $role->name }}
</div>
<div>
    <strong>{{ __('Permissions') }}:</strong>
    @if(!empty($rolePermissions))
        @foreach($rolePermissions as $v)
            <label>{{ $v->name }},</label>
        @endforeach
    @endif
</div>
@endsection