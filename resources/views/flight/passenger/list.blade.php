@extends('layouts.app')
@section('content')
@if (!empty($passengers))
    @foreach ($passengers as $passenger)
        <p><b>{{ __('Full Name') }}</b></p>
        <p>{{$passenger->name}}<p>
        <p><b>{{ __('Flight destination') }}</b></p>
        {{$passenger->flight->destination}}
        @foreach ($passenger->flight->flightsCrew()->get() as $crew)
            <p><b>{{ __('Main captain') }}</b></p>
            {{$crew->main_captain}}
        @endforeach
        @auth
            <p><a href="{{ url('auth/passenger/update/id', $passenger->id)}}">{{ __('UPDATE') }}</a></p>
        @endauth
    @endforeach
@endif
<div class="d-felx justify-content-center">
     {{ $passengers->links() }}
</div>
@endsection