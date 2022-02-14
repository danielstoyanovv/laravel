@extends('layouts.app')
@section('content')
<div class="pull-right">
    <a class="btn btn-primary" href="{{ route('flights.index') }}">{{ __('Back') }}</a>
</div>
@if (!empty($flight))
    <h2>{{ __('Show flight data') }}</h2>
    @if (!empty($flight->destination))
        <p>{{ __('Destination') }}: {{ $flight->destination }}</p>
    @endif
    @if (!empty($flight->price))
        <p>{{ __('Price') }}: {{ $flight->price }}</p>
    @endif
    @if (!empty($flight->date))
        <p>{{ __('Date') }}: {{ $flight->date }}</p>
    @endif
    @if (!empty($flight->destination_image))
        <p>{{ __('Destination image') }}<img src="<?= Storage::url($flight->destination_image); ?>" width="250px" /></p>
    @endif
    @if (!empty($flight->destination_data))
        <p>{{ __('Destination data') }}: <a href="<?= Storage::url($flight->destination_data); ?>">{{ __('Destination data') }}</a>
    @endif
@endif
@endsection