@extends('layouts.app')
@section('content')
<h2>{{ __('Create new product') }}</h2>
@if (count($errors) > 0)
    <ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    </ul>
@endif
{!! Form::open(array('route' => 'products.store','method'=>'POST', 'files' => false)) !!}

<div>
    <strong>{{ __('Name') }}:</strong>
    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
</div>
<div>
    <strong>{{ __('Price') }}:</strong>
    {!! Form::text('price', null, array('placeholder' => 'Price','class' => 'form-control')) !!}
</div>
<div>
    <strong>{{ __('Sku') }}:</strong>
    {!! Form::text('sku', null, array('placeholder' => 'Sku','class' => 'form-control')) !!}
</div>
<div>
    <strong>{{ __('Type') }}:</strong>
    {!! Form::select('type_id', [NULL => 'Select type', 'simple' => 'Simple', 'configurable' => 'Configurable'], [], array('class' => 'form-control')) !!}
</div>
<div>
    <strong>{{ __('Attribute set') }}:</strong>
    {!! Form::select('attribute_set_id', $attributeSets, [], array('class' => 'form-control')) !!}
</div>
<div>
    <strong>{{ __('Status') }}:</strong>
    {!! Form::select('status', [NULL => 'Select status', '0' => 'Disabled', '1' => 'Enabled'], [], array('class' => 'form-control')) !!}
</div>
<button>{{ __('Create') }}</button>
{!! Form::close() !!}
@endsection