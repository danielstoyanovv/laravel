@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
                <div>
                    <p><a href="/flight/list">{{ __('Flights') }}</a></p>
                    <p><a href="/auth/flight/create">{{ __('Create new flight') }}</a></p>
                    <p><a href="/flightcrew/list">{{ __('Flights Crew') }}</a></p>
                    <p><a href="/auth/flightcrew/create">{{ __('Create new flight crew') }}</a></p>
                    <p><a href="/auth/passenger/create">{{ __('Create new passenger') }}</a></p>
                    <p><a href="/passenger/list">{{ __('Passenger') }}</a></p>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
