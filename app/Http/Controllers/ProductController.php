<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * show all products came with Magento 2 rest api call
     * @return Response
     */
    public function index()
    {
        try {
            $client = $this->getClient();
            $products = $this->getProducts($client);
            $currency = $this->getCurrencyData($client);
            
            if ($products && $currency) { 
                return view('product.index', ['products' => $products, 'currency' => $currency]);
            }
        } catch (\Exception $e) {
            //die($e->getMessage());
            Log::error($e->getMessage());

        }
        return redirect()->route('home');
    }

    /**
     * Get client
     * @return \GuzzleHttp\Client
     */
    private function getClient()
    {
        return new \GuzzleHttp\Client(['headers' => ['Authorization' => config('magento.restapi_token')]]);
    }

    /**
     * Get all magento products with rest api call
     * @param \GuzzleHttp\Client $client
     * @return array
     */
    private function getProducts(\GuzzleHttp\Client $client): array
    {
        $response = $client->request(
            'GET', 
            config('magento.query_all_products')
        );
        return json_decode($response->getBody(), true);
    }

    /**
     * get currency data from magento withs rest api call
     * @param \GuzzleHttp\Client $client
     * @return array
     */
    private function getCurrencyData(\GuzzleHttp\Client $client): array
    {
        $responseCurrency = $client->request(
            'GET', 
            config('magento.currency_data')
        );
       return json_decode($responseCurrency->getBody(), true);
    }
}