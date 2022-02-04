@include("alerts")
<h2><?= Lang::get('Create new flight crew'); ?></h2>
<form method="POST" action="/flightcrew/update/id/<?php if (!empty($flightCrew)) : ?><?= $flightCrew->crew_id ?><?php endif; ?>" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="main_captain"><?= Lang::get('Main  captain'); ?></label>
        <input value="<?php if (!empty($flightCrew)) : ?><?= $flightCrew->main_captain ?><?php endif; ?>" id="main_captain" name="main_captain" type="text" class="@error('main_captain') is-invalid @enderror">
        @error('main_captain')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <br />
    <div>
        <label for="captain"><?= Lang::get('Captain'); ?></label>
        <input value="<?php if (!empty($flightCrew)) : ?><?= $flightCrew->captain ?><?php endif; ?>" id="captain" name="captain" type="text" class="@error('captain') is-invalid @enderror">

        @error('captain')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <br />
    <div>
        <label for="crew_member_1"><?= Lang::get('Crew member 1'); ?></label>
        <input value="<?php if (!empty($flightCrew)) : ?><?= $flightCrew->crew_member_1 ?><?php endif; ?>" id="crew_member_1" name="crew_member_1" type="text" class="@error('crew_member_1') is-invalid @enderror">

        @error('crew_member_1')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <br />
    <div>
        <label for="crew_member_2"><?= Lang::get('Crew member 2'); ?></label>
        <input value="<?php if (!empty($flightCrew)) : ?><?= $flightCrew->crew_member_2 ?><?php endif; ?>" id="crew_member_2" name="crew_member_2" type="text" class="@error('crew_member_2') is-invalid @enderror">

        @error('crew_member_2')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <br />
    <div>
        <label for="crew_member_3"><?= Lang::get('Crew member 3'); ?></label>
        <input value="<?php if (!empty($flightCrew)) : ?><?= $flightCrew->crew_member_3 ?><?php endif; ?>" id="crew_member_3" name="crew_member_3" type="text" class="@error('crew_member_3') is-invalid @enderror">

        @error('crew_member_3')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <br />
    <button><?= Lang::get('Update'); ?></button>
</form>