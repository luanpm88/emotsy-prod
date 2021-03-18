<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Lazada\LazadaConnection;
use Acelle\Model\Product;

class Source extends Model
{
    public static $itemsPerPage = 25;
    public $service;

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            while (self::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
    }

    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }

    /**
     * Customer.
     *
     * @return object
     */
    public function customer(){
    	return $this->belongsTo('Acelle\Model\Customer');
    }

    /**
     * Products.
     *
     * @return object
     */
    public function products(){
    	return $this->hasMany('Acelle\Model\Product');
    }

    /**
     * Get data.
     *
     * @var object | collect
     */
    public function getData()
    {
        if (!$this['data']) {
            return json_decode('{}', true);
        }

        return json_decode($this['data'], true);
    }

    /**
     * Update data.
     *
     * @var object | collect
     */
    public function updateData($data)
    {
        $data = (object) array_merge((array) $this->getData(), $data);
        $this['data'] = json_encode($data);

        $this->save();
    }

    /**
     * Search.
     *
     * @var object | collect
     */
    public function scopeSearch($query, $request)
    {
        $query = $query->where('customer_id', '=', $request->user()->id);
        
        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('sources.type', 'like', '%'.strtolower($keyword).'%');
                });
            }
        }

        return $query;
    }

    /**
     * Check if source connected.
     *
     * @var object | collect
     */
    public function connected()
    {
        return isset($this->id);
    }

    /**
     * Get service.
     *
     * @var object | collect
     */
    public function service()
    {
        if ($this->service) {
            return $this->service;
        }

        $this->service = new LazadaConnection(false, false, $this->getData());
        return $this->service;
    }

    /**
     * Update access token.
     *
     * @var object | collect
     */
    public function getAccessToken($code)
    {
        // get access token by code
        $this->service()->getAccessToken($code);

        // update data
        $this->updateData($this->service()->data);
    }

    public function importProducts($customer) {
        if ($this->type == 'woocommerce') {
            $client = new \GuzzleHttp\Client();
            $uri =  $this->getData()['connect_url'] . 
                    '&action=list&max=100&sort_by=';

            $response = $client->request('GET', $uri, [
                'Content-Type' => 'application/json',
            ]);
            $products = json_decode($response->getBody(), true);

            $total = count($products);
            $imported = 0;

            foreach ($products as $key => $product) {
                try {
                    // find exist product
                    $p = Product::where('source_item_id', '=', $product["id"])->first();

                    if (!$p) {
                        $p = new Product();
                        $p->source_item_id = $product["id"];
                        $p->customer_id = $customer->id;
                        $p->source_id = $this->id;
                    }

                    $p->title = $product["name"];
                    $p->description = $product["description"];
                    $p->meta = json_encode($product);
                    $p->save();

                    // upload image
                    if (isset($product["image"])) {
                        $p->uploadImage($product["image"]);
                    }
                    
                    $p->save();

                    $imported++;
                    // importing
                    $this->updateData([
                        'sync' => [
                            'status' => 'importing',
                            'imported' => $imported,
                            'total' => $total,
                            'progress' => round(($imported / $total) * 100, 2),
                        ]
                    ]);
                } catch(\Exception $e) {
                    // write error
                    \Log::error($e->getMessage());
                }
                    
            }

            return;
        }

        $data = $this->service()->getProducts(['offset' => 0, 'limit' => 1])['data'];
        $total = $data['total_products'];

        // starting
        $this->updateData([
            'sync' => [
                'status' => 'starting',
                'imported' => 0,
                'total' => $total,
                'progress' => 0,
            ]
        ]);
        
        $perPage = 12;
        $pages = ceil($total/$perPage);
        $imported = 0;
        for ($i=0; $i < $pages; $i++) {
            $products = $this->service()->getProducts(['offset' => $i*$perPage, 'limit' => $perPage])['data']['products'];
            foreach ($products as $key => $product) {
                // try {
                    // find exist product
                    $p = Product::where('source_item_id', '=', $product["item_id"])->first();

                    if (!$p) {
                        $p = new Product();
                        $p->source_item_id = $product["item_id"];
                        $p->customer_id = $customer->id;
                        $p->source_id = $this->id;
                    }

                    $p->title = $product["attributes"]["name"];
                    if (isset($product["attributes"]["short_description"])) {
                        $p->description = $product["attributes"]["short_description"];
                    }
                    $p->meta = json_encode($product);
                    $p->save();

                    // upload image
                    if (isset($product["skus"][0]["Images"]) && isset($product["skus"][0]["Images"][0])) {
                        $p->uploadImage($product["skus"][0]["Images"][0]);
                    }

                    // get price
                    if (isset($product["skus"][0]["price"])) {
                        $p->price = $product["skus"][0]["price"];
                    }

                    $p->save();

                    $imported++;
                    // importing
                    $this->updateData([
                        'sync' => [
                            'status' => 'importing',
                            'imported' => $imported,
                            'total' => $total,
                            'progress' => round(($imported / $total) * 100, 2),
                        ]
                    ]);
                // } catch(\Exception $e) {
                //     // write error
                //     \Log::error($e->getMessage());
                // }
                    
            }

            sleep(3);
        } 

        // done
        $this->updateData([
            'sync' => [
                'status' => 'done',
                'imported' => $this->getData()['sync']['imported'],
                'total' => $this->getData()['sync']['total'],
                'progress' => 100,
            ]
        ]);
    }

    /**
     * Products count.
     *
     * @return object
     */
    public function productsCount(){
    	return $this->products()->count();
    }
    
    /**
     * Get display name.
     *
     * @return object
     */
    public function getName(){
    	switch($this->type) {
            case 'woocommerce':
                return $this->getData()['data']['name'];
            default:
                return trans('messages.source.' . $this->type);
        }
    }
}
