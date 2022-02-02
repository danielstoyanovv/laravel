@foreach ($flights as $flight)
    <p>
        {{$flight->id}} - {{$flight->destination}} - <?= Lang::get('Price'); ?>: {{$flight->price}} -  
        <?= Lang::get('Date'); ?>: {{$flight->date}} <a href="{{ url('flight/update/id', $flight->id)}}">
        <?= Lang::get('UPDATE'); ?></a>
    </p>
    <?php if ($flight->destination_image) : ?>
        <img src="<?= Storage::url($flight->destination_image); ?>" width="250px" />
    <?php endif; ?>
    <?php if ($flight->destination_data) : ?>
        <a href="<?= Storage::url($flight->destination_data); ?>"><?= Lang::get('Destination data'); ?></a>
    <?php endif; ?>
@endforeach
<div class="d-felx justify-content-center">
     {{ $flights->links() }}
</div>