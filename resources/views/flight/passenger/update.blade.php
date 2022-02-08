@include("alerts")
@if ($passenger)
    <h2><?= Lang::get('Create new passenger'); ?></h2>
    <form method="POST" action="/passenger/update/id/{{$passenger->id}}" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name"><?= Lang::get('Full Name'); ?></label>
            <input value="@if ($passenger->name){{$passenger->name}}@endif" id="name" name="name" type="text" class="@error('name') is-invalid @enderror">
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        @if (!empty($flights))
            <label for="flight_id"><?= Lang::get('Select flight'); ?></label>
            <select name='flight_id' class="@error('flight_id') is-invalid @enderror">
                <option value=""></option>
                @foreach ($flights as $flight)
                    @if ($flight->destination)
                        <option value="{{$flight->id}}" @if ($passenger->flight_id == $flight->id) SELECTED @endif>
                            {{$flight->destination}}
                    @endif
                @endforeach
            </select>
            @error('flight_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        @endif
        
        <br />
        <button><?= Lang::get('Update'); ?></button>
    </form>
@endif