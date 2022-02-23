<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * create
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {       
        return view('auth.product.create', [
            'attributeSets' => $this->getMagentoAttributeSets()
        ]);
    }

    /**
     * Get client
     * @return \GuzzleHttp\Client
     */
    private function getClient()
    {
        return new \GuzzleHttp\Client(['headers' => [
            'Authorization' => config('magento.restapi_token'),
            'Content-Type' => 'application/json'
        ]]);
    }

    /**
     * store
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post') && $request) {
            $validated = $this->processValidate($request);
            try {
                $this->processData($validated, __('Product is created!'));
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        return redirect()->route('products');

    }

    /**
     * process validate
     *
     * @param Request $request
     * @return array
     */
    private function processValidate(Request $request): array
    {
        return $request->validate([
            'name' => 'required|max:50',
            'price' => 'required|numeric',
            'sku' => 'required|max:50',
            'type_id' => 'required',
            'attribute_set_id' => 'required',
            'status' => 'required'
        ]);
    }

    /**
     * process data
     *
     * @param array $validated
     * @param string $message
     * @return void
     */
    private function processData(array $validated, string $message)
    {
        
        if ($validated && $message) {
            $client = $this->getClient();
            $options = [];
            foreach ($validated as $k => $v) {
                $options['product'][$k] = $v;
            }

            $client->post(config("magento.create_update_product"), [
                \GuzzleHttp\RequestOptions::JSON => $options,
            ]);
            session()->flash('message', $message);
        }  
    }

    /**
     * get magento website attribute sets data with rest api call
     * @return array
     */
    private function getMagentoAttributeSets(): array
    {
        $setData = [];
        $client = $this->getClient();
        $response = $client->request('GET', config('magento.attribute_sets'));
        $atributeSets = json_decode($response->getBody(), true);
        if ($atributeSets && !empty($atributeSets['items'])) {
            foreach ($atributeSets['items'] as $set) {
                if ($set['attribute_set_id'] && $set['attribute_set_name']) {
                    $setData[$set['attribute_set_id']] = $set['attribute_set_name'];
                }
            }
        }
        return $setData;
    }
}