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
                    <p><a href="/flight/list"><?= Lang::get('Flights'); ?></a></p>
                    <p><a href="/flight/create"><?= Lang::get('Create new flight'); ?></a></p>
                    <p><a href="/flightcrew/list"><?= Lang::get('Flights Crew'); ?></a></p>
                    <p><a href="/flightcrew/create"><?= Lang::get('Create new flight crew'); ?></a></p>
                    <p><a href="/passenger/create"><?= Lang::get('Create new passenger'); ?></a></p>
                    <p><a href="/passenger/list"><?= Lang::get('Passenger'); ?></a></p>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
