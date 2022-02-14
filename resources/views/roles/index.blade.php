@extends('layouts.app')
@section('content')
<h2>{{ __('Role Management') }}</h2>
@can('role-create')
    <a href="{{ route('roles.create') }}">{{ __('Create New Role') }}</a>
@endcan

@if ($message = Session::get('success'))
    <div>
        <p>{{ $message }}</p>
    </div>
@endif

<table>
  <tr>
     <th>{{ __('No') }}</th>
     <th>{{ __('Name') }}</th>
     <th width="280px">{{ __('Action') }}</th>
  </tr>
    @foreach ($roles as $key => $role)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $role->name }}</td>
        <td>
            <a href="{{ route('roles.show',$role->id) }}">{{ __('Show') }}</a>
            @can('role-edit')
                <a href="{{ route('roles.edit',$role->id) }}">{{ __('Edit') }}</a>
            @endcan
            @can('role-delete')
                {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            @endcan
        </td>
    </tr>
    @endforeach
</table>
{!! $roles->render() !!}
@endsection