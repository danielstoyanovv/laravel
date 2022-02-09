@extends('layouts.app')
@section('content')
@foreach ($flightsCrew as $flightCrew)
    <div>
        <p><?= Lang::get('Crew id'); ?> - {{$flightCrew->id}}</p>
        <p><?= Lang::get('Main captain'); ?> - {{$flightCrew->main_captain}}</p> 
        <p><?= Lang::get('Captain'); ?> - {{$flightCrew->captain}}</p>   
        <p><?= Lang::get('Crew member 1'); ?> - {{$flightCrew->crew_member_1}}</p>   
        <p><?= Lang::get('Crew member 2'); ?> - {{$flightCrew->crew_member_2}}</p>   
        <p><?= Lang::get('Crew member 3'); ?> - {{$flightCrew->crew_member_3}}</p>
        <p><?= Lang::get('Crew member 1'); ?> - {{$flightCrew->crew_member_1}}</p>   
        @if ($flights)
            @foreach ($flights as $flight)
                @if ($flight->id == $flightCrew->flight_id)
                    @if (!empty($flight->destination))
                        <p><?= Lang::get('Flight destination'); ?></p>
                        {{$flight->destination}}
                    @endif
                @endif
            @endforeach
        @endif
        <p><a href="{{ url('flightcrew/update/id', $flightCrew->id)}}"></p>
        <?= Lang::get('UPDATE'); ?></a>

        <a href="{{ url('flightcrew/delete/id', $flightCrew->id)}}"><?= Lang::get('DELETE'); ?></a>
    </div>
@endforeach
<div class="d-felx justify-content-center">
     {{ $flightsCrew->links() }}
</div>
@stop