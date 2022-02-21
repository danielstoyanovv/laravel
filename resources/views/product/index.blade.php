@extends('layouts.app')
@section('content')
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
            <td>{{ $product['attribute_set_id'] }}</td>
            <td>
                @if (!empty($product['media_gallery_entries'][0]['file']))
                    <img src="http://magentolocal.com/pub/media/catalog/product{{ $product['media_gallery_entries'][0]['file'] }}" width="100px" />
                @endif
            </td>
        </tr>
        @endforeach 
    </table>
@endif
@endsection