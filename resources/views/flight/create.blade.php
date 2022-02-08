@include("alerts")
<h2><?= Lang::get('Create new flight'); ?></h2>
<form method="POST" action="/flight/create" enctype="multipart/form-data">
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
    <p>
        <label for="destination_image"><?= Lang::get('Destination image'); ?></label>
        <input id="destination_image" name="destination_image" type="file" class="@error('destination_image') is-invalid @enderror">

        @error('destination_image')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </p>
    <p>
        <label for="destination_data"><?= Lang::get('Destination data'); ?></label>
        <input id="destination_data" name="destination_data" type="file" class="@error('destination_data') is-invalid @enderror">

        @error('destination_data')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </p>
    <button><?= Lang::get('Create'); ?></button>
</form>