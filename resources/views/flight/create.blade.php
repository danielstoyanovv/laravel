@if(session('success'))
    <h1>{{session('success')}}</h1>
@endif
<h2><?= Lang::get('Create new flight'); ?></h2>
<form method="POST" action="/flight/create">
    @csrf
    <p>
        <label for="destination"><?= Lang::get('Destination'); ?></label>
        <input id="destination" name="destination" type="text" class="@error('destination') is-invalid @enderror">
        @error('destination')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </p>
    <p>
        <label for="price"><?= Lang::get('Price'); ?></label>
        <input id="price" name="price" type="text" class="@error('price') is-invalid @enderror">

        @error('price')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </p>
    <p>
        <label for="date"><?= Lang::get('Date'); ?></label>
        <input id="date" name="date" type="text" class="@error('date') is-invalid @enderror">

        @error('date')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </p>
    <button><?= Lang::get('Submit'); ?></button>
</form>