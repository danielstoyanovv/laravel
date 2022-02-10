@extends('layouts.app')
@section('content')
<h2><?= Lang::get('Create new flight crew'); ?></h2>
<form method="POST" action="/auth/flightcrew/create" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="main_captain"><?= Lang::get('Main  captain'); ?></label>
        <input id="main_captain" name="main_captain" type="text" class="@error('main_captain') is-invalid @enderror">
        @error('main_captain')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <br />
    <div>
        <label for="captain"><?= Lang::get('Captain'); ?></label>
        <input id="captain" name="captain" type="text" class="@error('captain') is-invalid @enderror">

        @error('captain')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <br />
    <div>
        <label for="crew_member_1"><?= Lang::get('Crew member 1'); ?></label>
        <input id="crew_member_1" name="crew_member_1" type="text" class="@error('crew_member_1') is-invalid @enderror">

        @error('crew_member_1')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <br />
    <div>
        <label for="crew_member_2"><?= Lang::get('Crew member 2'); ?></label>
        <input id="crew_member_2" name="crew_member_2" type="text" class="@error('crew_member_2') is-invalid @enderror">

        @error('crew_member_2')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <br />
    <div>
        <label for="crew_member_3"><?= Lang::get('Crew member 3'); ?></label>
        <input id="crew_member_3" name="crew_member_3" type="text" class="@error('crew_member_3') is-invalid @enderror">

        @error('crew_member_3')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    @if (!empty($flights))
        <div>
            <label for="flight_id"><?= Lang::get('Select flight destination'); ?></label>
            <select name='flight_id' class="@error('flight_id') is-invalid @enderror">
                <option value=""></option>
                @foreach ($flights as $flight)
                    @if ($flight->destination)
                        <option value="{{$flight->id}}">{{$flight->destination}}
                    @endif
                @endforeach
            </select>
            @error('flight_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    @endif
    <button><?= Lang::get('Create'); ?></button>
</form>
@endsection