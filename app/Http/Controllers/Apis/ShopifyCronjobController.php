<?php

namespace App\Http\Controllers\Apis;

use App\Models\Beautyfortnewstock;
use App\Models\ShopifyRemovedProduct;
use App\Models\ShopifySellerProduct;
use App\Models\ShopifyUploadedProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Signifly\Shopify\Factory;

class ShopifyCronjobController
{
    private $shopify_api_url_base;
    private $apiheader;

    public function __construct()
    {
        $this->apiheader = array ("Content-Type: application/json");
        $this->shopify_api_url_base = 'https://'.config('shopify.credentials.api_key').':'.config('shopify.credentials.password').'@'.config('shopify.credentials.domain').'/admin/api/2021-07';
    }

    public function postProducts(Request $request)
    {
        $newstocks = Beautyfortnewstock::get()->toArray();
        foreach($newstocks as $newstock) {
            $price = $newstock['rrp']?:$newstock['price']*3;
            $quantity = $newstock['quantity']??0;
            $post_data = '{
                              "product": {
                                "title": "'.$newstock['full_name'].'",
                                "body_html": "<strong>'.$newstock['full_name'].'</strong>",
                                "vendor": "test_dropshipping921",
                                "product_type": "'.$newstock['collection'].'",
                                "status": "active",
                                "images": [{
                                            "src": "'.$newstock['high_res_image_url'].'"
                                          }],
                                "variants": [
                                  {
                                    "price": "'.$price.'",
                                    "barcode": "'.$newstock['barcode'].'",
                                    "inventory_quantity": '.$quantity.',
                                    "sku": "'.$newstock['stock_code'].'"
                                  }
                                ]
                              }
                          }';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$this->shopify_api_url_base.'/products.json');
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HEADER, 0); // 0 = Don't give me the return header
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->apiheader); // Set this for API Header
            curl_setopt($ch, CURLOPT_POST, 1); // POST Method
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // json Request
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $jsonResponse = curl_exec($ch); //Send the request
            curl_close($ch); // Close the connection
            $t = json_decode($jsonResponse, true);
            if ($t) {
                $t = $t['product'];
                $created_at = $t['created_at'] == null ? null : str_replace("T"," ", substr($t['created_at'], 0, 19));
                $updated_at = $t['updated_at'] == null ? null : str_replace("T"," ", substr($t['updated_at'], 0, 19));
                $published_at = $t['published_at'] == null ? null : str_replace("T"," ", substr($t['published_at'], 0, 19));
                $image = $t['image'] == null ? '' : $t['image']['src'];
                $quantity = 0;
                $barcode = '';
                $price = 0.0;
                $sku = '';
                if (count($t['variants']) > 0) {
                    $quantity = $t['variants'][0]['inventory_quantity'];
                    $barcode = $t['variants'][0]['barcode'];
                    $price = $t['variants'][0]['price'];
                    $sku = $t['variants'][0]['sku'];
                }
                ShopifyUploadedProduct::create([
                    'id' => $t['id'],
                    'title' => $t['title'],
                    'vendor' => $t['vendor'],
                    'product_type' => $t['product_type'],
                    'status' => $t['status'],
                    'published_scope' => $t['published_scope'],
                    'tags' => $t['tags'],
                    'image' => $image,
                    'quantity' => $quantity,
                    'barcode' => $barcode,
                    'sku' => $sku,
                    'price' => $price, //$t['variants'][0]['presentment_prices'][0]['price']['amount'],
                    'currency' => 'USD', //$t['variants'][0]['presentment_prices'][0]['price']['currency_code'],
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'published_at' => $published_at,
                ]);
            }
        }
        return response()->json(['result' => 'success']);
    }

    public function removeProducts(Request $request)
    {
        $productsToDelete = ShopifySellerProduct::join('beautyfortremovedstocks', 'shopify_seller_products.sku', '=', 'beautyfortremovedstocks.stock_code')
            ->select('shopify_seller_products.*')
            ->get();
        foreach ($productsToDelete as $item) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->shopify_api_url_base.'/products/'.$item['id'].'.json');
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HEADER, 0); // 0 = Don't give me the return header
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->apiheader); // Set this for API Header
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $jsonResponse = curl_exec($ch); //Send the request
            curl_close($ch); // Close the connection
            $result = json_decode($jsonResponse);
            if (property_exists($result, 'errors')) {
                continue;
            }
            ShopifyRemovedProduct::create([
                'id' => $item['id'],
                'title' => $item['title'],
                'vendor' => $item['vendor'],
                'product_type' => $item['product_type'],
                'status' => $item['status'],
                'published_scope' => $item['published_scope'],
                'tags' => $item['tags'],
                'image' => $item['tags'],
                'quantity' => $item['quantity'],
                'barcode' => $item['barcode'],
                'sku' => $item['sku'],
                'price' => $item['price'],
                'currency' => 'USD',
            ]);
        }
        return response()->json(['result' => 'success']);
    }

    public function getProducts(Request $request)
    {
        $shopify = Factory::fromConfig();
        $pages = $shopify->paginateProducts(['limit' => 100]); // returns Cursor
        $products = collect();
        foreach ($pages as $page) {
            // $page is a Collection of ApiResources
            $products = $products->merge($page);
        }
        if(count($products)>0) {
            ShopifySellerProduct::truncate();
            foreach ($products as $t)
            {
                $created_at = $t['created_at'] == null ? null : str_replace("T"," ", substr($t['created_at'], 0, 19));
                $updated_at = $t['updated_at'] == null ? null : str_replace("T"," ", substr($t['updated_at'], 0, 19));
                $published_at = $t['published_at'] == null ? null : str_replace("T"," ", substr($t['published_at'], 0, 19));
                $image = $t['image'] == null ? '' : $t['image']['src'];
                $quantity = 0;
                $barcode = '';
                $price = 0.0;
                $sku = '';
                if (count($t['variants']) > 0) {
                    $quantity = $t['variants'][0]['inventory_quantity'];
                    $barcode = $t['variants'][0]['barcode'];
                    $price = $t['variants'][0]['price'];
                    $sku = $t['variants'][0]['sku'];
                }
                ShopifySellerProduct::create([
                    'id' => $t['id'],
                    'title' => $t['title'],
                    'vendor' => $t['vendor'],
                    'product_type' => $t['product_type'],
                    'status' => $t['status'],
                    'published_scope' => $t['published_scope'],
                    'tags' => $t['tags'],
                    'image' => $image,
                    'quantity' => $quantity,
                    'barcode' => $barcode,
                    'sku' => $sku,
                    'price' => $price, //$t['variants'][0]['presentment_prices'][0]['price']['amount'],
                    'currency' => 'USD', //$t['variants'][0]['presentment_prices'][0]['price']['currency_code'],
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'published_at' => $published_at,
                ]);
            }
        }
//        return response()->json($products);
        return response()->json(['result' => 'success']);
    }

    public function removeAllTestProducts(Request $request)
    {
        $shopify = Factory::fromConfig();
        $pages = $shopify->paginateProducts(['limit' => 250]); // returns Cursor
        $products = collect();
        foreach ($pages as $page) {
            // $page is a Collection of ApiResources
            $products = $products->merge($page);
        }
        if(count($products)>0) {
            foreach ($products as $t)
            {
                $shopify->deleteProduct($t['id']);
            }
        }
        return response()->json(['result' => 'success']);
    }
}
