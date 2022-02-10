@extends('layouts.app')
@section('content')
<h2>{{ __('Create new passenger') }}</h2>
<form method="POST" action="/auth/passenger/create" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="name">{{ __('Full Name') }}</label>
        <input id="name" name="name" type="text" class="@error('name') is-invalid @enderror">
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    @if (!empty($flights))
        <div>
            <label for="flight_id">{{ __('Select flight') }}</label>
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
    <button>{{ __('Create') }}</button>
</form>
@endsection