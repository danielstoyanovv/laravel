@include("alerts")
@if (!empty($passengers))
    @foreach ($passengers as $passenger)
        <p><b><?= Lang::get('Passenger name'); ?></b></p>
        <p>{{$passenger->name}}<p>
        <p><b><?= Lang::get('Flight destination'); ?></b></p>
        {{$passenger->flight->destination}}
        @foreach ($passenger->flight->flightsCrew()->get() as $crew)
            <p><b><?= Lang::get('Main captain'); ?></b></p>
            {{$crew->main_captain}}
        @endforeach
    @endforeach
@endif
<div class="d-felx justify-content-center">
     {{ $passengers->links() }}
</div>