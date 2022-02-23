@extends('layouts.app')
@section('content')
@auth
    <p><a href="{{ route('products.create') }}">{{ __('Create new product') }}</a>
@endauth
@if (!empty($products['items']))
    <table style="border: 1px solid black;">
        <tr>
            <th>{{ __('Id')}}</th>
            <th>{{ __('Name')}}</th>
            <th>{{ __('Price')}}</th>
            <th>{{ __('Sku')}}</th>
            <th>{{ __('Attribute set')}}</th>
            <th>{{ __('Status')}}</th>
            <th>{{ __('Visibility')}}</th>
            <th>{{ __('Type')}}</th>
            <th>{{ __('Created at')}}</th>
            <th>{{ __('Image')}}</th>
            <th>{{ __('Custom attributes')}}</th>
        </tr>
        @foreach ($products['items'] as $product)
        <tr>        
            <td>{{ $product['id'] }}</td>
            <td>{{ $product['name'] }}</td>
            <td>{{ $product['price'] }}</td>
            <td>{{ $product['sku'] }}</td>
            <td>{{ $product['attribute_set_id'] }}</td>
            <td>{{ $product['status'] }}</td>
            <td>{{ $product['visibility'] }}</td>
            <td>{{ $product['type_id'] }}</td>
            <td>{{ $product['created_at'] }}</td>
            <td>
                @if (!empty($product['media_gallery_entries'][0]['file']))
                    <img src="http://magentolocal.com/pub/media/catalog/product{{ $product['media_gallery_entries'][0]['file'] }}" width="100px" />
                @endif
            </td>
            <td>
            @if (!empty($product['custom_attributes'])) 
                @foreach ($product['custom_attributes'] as $attribute)
                    @if (!is_array($attribute['value']))
                        <p><b>{{ $attribute['attribute_code'] }}</b>: {{ $attribute['value'] }}</p>
                    @endif
                @endforeach
            @endif
            </td>
        </tr>
        @endforeach 
    </table>
@endif
@endsection