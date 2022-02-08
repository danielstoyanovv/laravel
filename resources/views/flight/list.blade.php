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
        <div>
            <p><b><?= Lang::get('Flight main captain'); ?>:</b> {{$flight->flightsCrew->main_captain}}</p>
            <p><b><?= Lang::get('Flight captain'); ?>:</b> {{$flight->flightsCrew->captain}}</p>
            <p><b><?= Lang::get('Flight member 1'); ?>:</b> {{$flight->flightsCrew->crew_member_1}}</p>
            <p><b><?= Lang::get('Flight member 2'); ?>:</b> {{$flight->flightsCrew->crew_member_2}}</p>
            <p><b><?= Lang::get('Flight member 3'); ?>:</b> {{$flight->flightsCrew->crew_member_3}}</p>
        </div>
    @endif
    @if ($flight->passenger)
        <div>
            @foreach ($flight->passenger()->get() as $passenger)
            <p><b><?= Lang::get("Flight passenger"); ?>:</b> {{$passenger->name}}</p>
            @endforeach
        </div>
    @endif
@endforeach
<div class="d-felx justify-content-center">
     {{ $flights->links() }}
</div>