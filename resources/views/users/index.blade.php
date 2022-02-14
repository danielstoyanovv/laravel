@extends('layouts.app')

@section('content')
<h2>{{ __('Users Management') }}</h2>

<a href="{{ route('users.create') }}">{{ __('Create New User') }}</a>

@if ($message = Session::get('success'))
  <p>{{ $message }}</p>
@endif

<table>
 <tr>
   <th>{{ __('No') }}</th>
   <th>{{ __('Name') }}</th>
   <th>{{ __('Email') }}</th>
   <th>{{ __('Roles') }}</th>
   <th width="280px">{{ __('Action') }}</th>
 </tr>
 @foreach ($data as $key => $user)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
        @if(!empty($user->getRoleNames()))
            @foreach($user->getRoleNames() as $v)
                <label>{{ $v }}</label>
            @endforeach
        @endif
        </td>
        <td>
            <a href="{{ route('users.show',$user->id) }}">{{ __('Show') }}</a>
            <a href="{{ route('users.edit',$user->id) }}">{{ __('Edit') }}</a>
            {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id], 'style'=>'display:inline']) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
        </td>
    </tr>
 @endforeach
</table>
{!! $data->render() !!}
@endsection