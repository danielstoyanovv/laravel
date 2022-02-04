@include("alerts")
@foreach ($flightsCrew as $flightCrew)
    <div>
        <?= Lang::get('Crew id'); ?> - {{$flightCrew->crew_id}}   
        <br />
        <?= Lang::get('Main captain'); ?> - {{$flightCrew->main_captain}}
        <br  />
        <?= Lang::get('Captain'); ?> - {{$flightCrew->captain}}
        <br />
        <?= Lang::get('Crew member 1'); ?> - {{$flightCrew->crew_member_1}}
        <br />
        <?= Lang::get('Crew member 2'); ?> - {{$flightCrew->crew_member_2}}
        <br />
        <?= Lang::get('Crew member 3'); ?> - {{$flightCrew->crew_member_3}}
        <br />
        <?= Lang::get('Crew member 1'); ?> - {{$flightCrew->crew_member_1}}
        <br />
        <a href="{{ url('flightcrew/update/id', $flightCrew->crew_id)}}">
        <?= Lang::get('UPDATE'); ?></a>

        <a href="{{ url('flightcrew/delete/id', $flightCrew->crew_id)}}"><?= Lang::get('DELETE'); ?></a>
    </div>
    <br />
@endforeach
<div class="d-felx justify-content-center">
     {{ $flightsCrew->links() }}
</div>