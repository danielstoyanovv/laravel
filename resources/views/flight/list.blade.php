@foreach ($flights as $flight)
    <p>
        {{$flight->id}} - {{$flight->destination}} - {{$flight->price}} - {{$flight->date}} <a href="{{ url('flight/update/id', $flight->id)}}"><?= Lang::get('UPDATE'); ?></a>
    </p>
@endforeach
<div class="d-felx justify-content-center">
     {{ $flights->links() }}
</div>