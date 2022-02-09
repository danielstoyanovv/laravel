@extends('layouts.app')
@section('content')
<h2><?= Lang::get('Create new flight'); ?></h2>
<form method="POST" action="/flight/update/id/<?php if (!empty($flight)) : ?><?= $flight->id ?><?php endif; ?>" enctype="multipart/form-data">
    @csrf
    <p>
        <label for="destination"><?= Lang::get('Destination'); ?></label>
        <input value="@if (!empty($flight->destination)){{$flight->destination}}@endif" id="destination" name="destination" type="text" class="@error('destination') is-invalid @enderror">
        @error('destination')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </p>
    <p>
        <label for="price"><?= Lang::get('Price'); ?></label>
        <input value="@if (!empty($flight->price)){{$flight->price}}@endif" id="price" name="price" type="text" class="@error('price') is-invalid @enderror">

        @error('price')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </p>
    <p>
        <label for="date"><?= Lang::get('Date'); ?></label>
        <input value="@if (!empty($flight->date)){{$flight->date}}@endif" id="date" name="date" type="text" class="@error('date') is-invalid @enderror">

        @error('date')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </p>
    <p>
        <label for="destination_image"><?= Lang::get('Destination image'); ?></label>
        <input value="@if (!empty($flight->destination_image)){{$flight->destination_image}}@endif" id="destination_image" name="destination_image" type="file" class="@error('destination_image') is-invalid @enderror">

        @error('destination_image')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </p>
    <p>
    <?php if ($flight->destination_image) : ?>
        <img src="<?= Storage::url($flight->destination_image); ?>" width="250px" />
    <?php endif; ?>
    </p>
    <p>
        <label for="destination_data"><?= Lang::get('Destination data'); ?></label>
        <input value="@if (!empty($flight->destination_data)){{$flight->destination_data}}@endif" id="destination_data" name="destination_data" type="file" class="@error('destination_data') is-invalid @enderror">

        @error('destination_data')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </p>
    <p>
    <?php if ($flight->destination_data) : ?>
        <a href="<?= Storage::url($flight->destination_data); ?>"><?= Lang::get('Destination data'); ?></a>
    <?php endif; ?>
    </p>
    <button><?= Lang::get('Update'); ?></button>
</form>
@endsection