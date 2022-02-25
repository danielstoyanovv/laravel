@extends('layouts.app')
@section('content')
<h2>{{ __('Edit product') }}</h2>
@if (count($errors) > 0)
    <ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    </ul>
@endif
@if (!empty($product['sku']))
    {!! Form::open(array('route' => ['products.update', $product['id']], 'method'=>'PATCH', 'files' => true)) !!}
    <div>
        <strong>{{ __('Name') }}:</strong>
        {!! Form::text('name', !empty($product['name']) ? $product['name'] : '', array('placeholder' => 'Name','class' => 'form-control')) !!}
    </div>
    <div>
        <strong>{{ __('Price') }}:</strong>
        {!! Form::text('price', !empty($product['price']) ? $product['price'] : '', array('placeholder' => 'Price','class' => 'form-control')) !!}
    </div>
    <div>
        <strong>{{ __('Sku') }}:</strong>
        {!! Form::text('sku', !empty($product['sku']) ? $product['sku'] : '', array('placeholder' => 'Sku','class' => 'form-control')) !!}
    </div>
    <div>
        <strong>{{ __('Type') }}:</strong>
        {!! Form::select('type_id', [NULL => 'Select type', 'simple' => 'Simple', 'configurable' => 'Configurable'], [!empty($product['type_id']) ? $product['type_id'] : ''], array('class' => 'form-control')) !!}
    </div>
    <div>
        <strong>{{ __('Attribute set') }}:</strong>
        {!! Form::select('attribute_set_id', $attributeSets, [!empty($product['attribute_set_id']) ? $product['attribute_set_id'] : ''], array('class' => 'form-control')) !!}
    </div>
    <div>
        <strong>{{ __('Status') }}:</strong>
        {!! Form::select('status', ['0' => 'Disabled', '1' => 'Enabled'], [!empty($product['status']) ? $product['status'] : ''], array('class' => 'form-control')) !!}
    </div>
    <div>
        <strong>{{ __('Image') }}:</strong>
        @if (!empty($product['media_gallery_entries'][0]['file']))
            <img src="http://magentolocal.com/pub/media/catalog/product{{ $product['media_gallery_entries'][0]['file'] }}" width="100px" />
        @endif
        <input value="@if (!empty($product['media_gallery_entries'][0]['file'])){{ $product['media_gallery_entries'][0]['file'] }}@endif" name="image" type="file">

    </div>
    <button>{{ __('Update') }}</button>
    {!! Form::close() !!}
@endif
@endsection