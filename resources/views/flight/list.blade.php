@include("alerts")
@foreach ($flights as $flight)
    <div>
        {{$flight->id}} - {{$flight->destination}} - <?= Lang::get('Price'); ?>: {{$flight->price}} -  
        <?= Lang::get('Date'); ?>: {{$flight->date}} <a href="{{ url('flight/update/id', $flight->id)}}">
        <?= Lang::get('UPDATE'); ?></a>

        <a href="{{ url('flight/delete/id', $flight->id)}}"><?= Lang::get('DELETE'); ?></a>
    </div>
    @if ($flight->destination_image)
        <img src="<?= Storage::url($flight->destination_image); ?>" width="250px" />
    @endif
    @if ($flight->destination_data)
        <a href="<?= Storage::url($flight->destination_data); ?>"><?= Lang::get('Destination data'); ?></a>
    @endif
    @if ($flight->flightsCrew)
        @foreach ($flight->flightsCrew()->get() as $crew)
            @if ($crew->main_captain)
                <h3><?= Lang::get('Flight Crew'); ?></h3>
                <p><b><?= Lang::get('Main captain'); ?>:</b> {{$crew->main_captain}}<p>
            @endif
            
            @if ($crew->captain)
                <p><b><?= Lang::get('Captain'); ?>:</b> {{$crew->captain}}<p>
            @endif
            @if ($crew->crew_member_1)
                <p><b><?= Lang::get('Crew member 1'); ?>:</b> {{$crew->crew_member_1}}<p>
            @endif
            @if ($crew->crew_member_2)
                <p><b><?= Lang::get('Crew member 2'); ?>:</b> {{$crew->crew_member_2}}<p>
            @endif
            @if ($crew->crew_member_3)
                <p><b><?= Lang::get('Crew member 3'); ?>:</b> {{$crew->crew_member_3}}<p>
            @endif
        @endforeach
    @endif
@endforeach
<div class="d-felx justify-content-center">
     {{ $flights->links() }}
</div>