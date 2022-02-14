@extends('layouts.app')
@section('content')
<div class="pull-right">
    <a class="btn btn-success" href="{{ route('flights.create') }}">{{ __('Create new flight') }}</a>
</div>
@foreach ($flights as $flight)
    <div>
        {{$flight->id}} - {{$flight->destination}} - {{ __('Price') }}: {{$flight->price}} -  
        <?= Lang::get('Date'); ?>: {{$flight->date}} 
        <a href="{{ route('flights.show',$flight->id) }}">{{ __('SHOW') }}</a>
        <a href="{{ route('flights.edit',$flight->id) }}">{{ __('UPDATE') }}</a>
        {!! Form::open(['method' => 'DELETE','route' => ['flights.destroy', $flight->id],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </div>
    @if ($flight->destination_image)
        <img src="<?= Storage::url($flight->destination_image); ?>" width="250px" />
    @endif
    @if ($flight->destination_data)
        <a href="<?= Storage::url($flight->destination_data); ?>">{{ __('Destination data') }}</a>
    @endif
    @if ($flight->flightsCrew)
        <div>
            <p><b>{{ __('Flight main captain') }}:</b> {{$flight->flightsCrew->main_captain}}</p>
            <p><b>{{ __('Flight captain') }}:</b> {{$flight->flightsCrew->captain}}</p>
            <p><b>{{ __('Flight member 1') }}:</b> {{$flight->flightsCrew->crew_member_1}}</p>
            <p><b>{{ __('Flight member 2') }}:</b> {{$flight->flightsCrew->crew_member_2}}</p>
            <p><b>{{ __('Flight member 3') }}:</b> {{$flight->flightsCrew->crew_member_3}}</p>
        </div>
    @endif
    @if ($flight->passenger)
        <div>
            @foreach ($flight->passenger()->get() as $passenger)
            <p><b>{{ __("light passenger") }}:</b> {{$passenger->name}}</p>
            @endforeach
        </div>
    @endif
@endforeach
<div class="d-felx justify-content-center">
     {{ $flights->links() }}
</div>
@endsection