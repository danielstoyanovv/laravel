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
                $this->processData($validated, $request, __('Product is created!'));
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
     * @param Request $request
     * @param string $message
     * @return void
     */
    private function processData(array $validated, Request $request, string $message)
    {
        
        if ($validated && $message) {
            $client = $this->getClient();
            $this->createProduct($validated, $client);
            $this->createProductImage($validated, $client, $request);
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

    /**
     * create product image with magento rest api call
     * @param array $validated
     * @param \GuzzleHttp\Client $client
     * @param Request $request
     * @return void
     */
    private function createProductImage(array $validated, \GuzzleHttp\Client $client, Request $request)
    {
        if ($validated && $client && $request && !empty($request->file('image'))) {
            $imageOptions = [];
            $imageOptions['entry'] = [
                "media_type" => "image",
                "label" => "Image",
                "position" => 1,
                "disabled" => false,
                "types" => [
                    "image",
                    "small_image",
                    "thumbnail"
                ],
                "content" => [
                    "base64_encoded_data" => base64_encode(file_get_contents($request->file('image')->getPathName())),
                    "type" => "image/" . $request->file('image')->getClientOriginalExtension(),
                    "name" => $request->file('image')->getClientOriginalName()
                ]
            ];
            $client->post(config("magento.create_update_product") . '/' . $validated['sku'] . "/media", [
                \GuzzleHttp\RequestOptions::JSON => $imageOptions,
            ]);
        }
    }

    /**
     * create product with magento rest api call
     * @param array $validated
     * @param \GuzzleHttp\Client $client
     * @return void
     */
    private function createProduct(array $validated, \GuzzleHttp\Client $client)
    {
        if ($validated && $client) {
            $options = [];
            foreach ($validated as $k => $v) {
                $options['product'][$k] = $v;
            }

            $client->post(config("magento.create_update_product"), [
                \GuzzleHttp\RequestOptions::JSON => $options,
            ]);
        }
    }

    /**
     * edit
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id)
    {
        try {
            if ($id) {
                $product = $this->getProduct($id);
                if (!empty($product['items'][0])) {
                    return view('auth.product.edit', [
                        'product' => $product['items'][0],
                        'attributeSets' => $this->getMagentoAttributeSets()
                    ]);
                }
            }
        } catch (\Exception $e) {
            //die($e->getMessage());
            Log::error($e->getMessage());
        }
        session()->flash('message', __('This product did not exists!'));
        return redirect()->route('products.create');
        
    }

    /**
     * update
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function update(int $id, Request $request)
    {
        if ($id && $request->isMethod('patch') && $request) {
            $validated = $this->processValidate($request);
            try {
                $this->removeImages($request, $validated);
                $this->processData($validated, $request, __('Product is updated!'));
                return redirect()->route('products.edit', $id);
            } catch (\Exception $e) {
                //die($e->getMessage());
                Log::error($e->getMessage());
            }
        }
        session()->flash('message', __('This product did not exists!'));
        return redirect()->route('products.create');
    }

    /**
     * get product with mageno rest api call
     * @param int $id
     */
    private function getProduct(int $id): array
    {
        $client = $this->getClient();
        $response = $client->request('GET', config('magento.create_update_product') . 
        "?searchCriteria[filterGroups][0][filters][0][field]=entity_id&searchCriteria[filterGroups][0][filters][0][condition_type]=
        eq&searchCriteria[filterGroups][0][filters][0][value]=" . $id);
        return json_decode($response->getBody(), true);
    }

    /**
     * remove magento product image/s with rest api call
     * 
     * @param Request $request
     * @param array $validated
     * @return void
     */
    private function removeImages(Request $request, array $validated)
    {
        if ($request && $validated && !empty($request->input('delete_image'))) {
            $client = $this->getClient();
            foreach ($request->input('delete_image') as $imageId) {
                $client->request('DELETE', config('magento.create_update_product') . "/" . $validated['sku'] . "/media/" . $imageId);
            }
        }
    }
}