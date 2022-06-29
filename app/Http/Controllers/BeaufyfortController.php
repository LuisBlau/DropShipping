<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Activity;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Psr7;
use Carbon\Carbon;

use App\Models\Beautyfortstock;
use App\Models\Beautyfortstockold;

use App\Models\Beautyfortnewstock;
use App\Models\Beautyfortremovedstock;

class BeaufyfortController
{
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    private function is_base64_encoded($data)
    {
        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function get(Request $request)
    {
        //$dt = new \DateTime(); //$dt->setTimeZone(new \DateTimeZone('Europe/London')); // A time in London.
        $username = 'liveapi';
        $secret = 'I6bBfDRrs5z4iqChMVWHp3FTeuwtkExvmJPKUnyYNLg8O';
        $nonce = $this->generateRandomString();
        $created = date('Y-m-d\TH:i:sP');
        $password = base64_encode(sha1($nonce.$created.$secret));

        $query = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:bf="http://www.beautyfort.com/api/">
            <soap:Header>
                <bf:AuthHeader>
                    <bf:Username>'.$username.'</bf:Username>
                    <bf:Nonce>'.$nonce.'</bf:Nonce>
                    <bf:Created>'.$created.'</bf:Created>
                    <bf:Password>'.$password.'</bf:Password>
                </bf:AuthHeader>
            </soap:Header>
            <soap:Body>
                <bf:GetStockFileRequest>
                    <bf:TestMode>false</bf:TestMode>
                    <bf:StockFileFormat>JSON</bf:StockFileFormat>
                    <bf:FieldDelimiter>,</bf:FieldDelimiter>
                    <bf:SortBy>Price</bf:SortBy>
                </bf:GetStockFileRequest>
            </soap:Body>
        </soap:Envelope>';

        try {
            $client = new Client;
            $response = $client->post(
                'http://www.beautyfort.com/api/soap',
                [
                    RequestOptions::BODY => $query,
                    RequestOptions::HEADERS => ['Content-Type' => 'text/xml']
                ]
            );
         
            $res = $response->getBody()->getContents();
            $res = explode("JSON",$res);
            $res = $res[1];
            $res = strip_tags($res);
            $res =  base64_decode($res); //json
            $res = json_decode($res, true);

            if(count($res)>0) {

                $cnt = DB::select( DB::raw("SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'beautyfortstocks_old'") );
                $cnt = $cnt[0]->cnt;
                if($cnt>0) {
                    DB::statement('RENAME TABLE `beautyfortstocks_old` TO `beautyfortstocks_temp`');
                }

                $cnt = DB::select( DB::raw("SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'beautyfortstocks_new'") );
                $cnt = $cnt[0]->cnt;
                if($cnt>0) {
                    DB::statement('RENAME TABLE `beautyfortstocks_new` TO `beautyfortstocks_old`');
                }
        
                $cnt = DB::select( DB::raw("SELECT COUNT(*) AS cnt FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'beautyfortstocks_temp'") );
                $cnt = $cnt[0]->cnt;
                if($cnt>0) {
                    DB::statement('RENAME TABLE `beautyfortstocks_temp` TO `beautyfortstocks_new`');
                }

                /*
                //--copy newstock to oldstock
                Beautyfortstockold::truncate();
                Beautyfortstock::query()
                ->each(function ($oldPost) {
                 $newPost = $oldPost->replicate();
                 $newPost->setTable('beautyfortstocks_old');
                 $newPost->save();
               });
                */
                //create newstock
                Beautyfortstock::truncate();
                /*
                foreach($res as $product) {
                    $stock = new Beautyfortstock();
                    $stock->stock_code = $product['StockCode'];
                    $stock->full_name = $product['FullName'];
                    $stock->stock_level = $product['StockLevel'];
                    $stock->rrp = $product['RRP']?$product['RRP']:null;
                    $stock->price = $product['Price'];
                    $stock->last_purchased_price = $product['LastPurchasedPrice']?$product['LastPurchasedPrice']:null;
                    $stock->barcode = $product['Barcode'];
                    $stock->collection = $product['Collection'];
                    $stock->high_res_image_url = $product['HighResImageUrl'];
                    $stock->brand = $product['Brand'];
                    $stock->quantity = $product['Quantity']?$product['Quantity']:null;
                    $stock->type = $product['Type'];
                    $stock->size = $product['Size']?$product['Size']:null;
                    $stock->gender = $product['Gender'];
                    $stock->category = $product['Category'];
                    $stock->save();
                }*/
                $products = [];
                foreach($res as $product) {
                    $products[] = [
                        'stock_code' => $product['StockCode'],
                        'full_name' => $product['FullName'],
                        'stock_level' => $product['StockLevel'],
                        'rrp' => $product['RRP']?$product['RRP']:null,
                        'price' => $product['Price'],
                        'last_purchased_price' => $product['LastPurchasedPrice']?$product['LastPurchasedPrice']:null,
                        'barcode' => $product['Barcode'],
                        'collection' => $product['Collection'],
                        'high_res_image_url' => $product['HighResImageUrl'],
                        'brand' => $product['Brand'],
                        'quantity' => $product['Quantity']?$product['Quantity']:null,
                        'type' => $product['Type'],
                        'size' => $product['Size']?$product['Size']:null,
                        'gender' => $product['Gender'],
                        'category' => $product['Category']
                    ];
                }
                foreach (array_chunk($products, 1000) as $t)  
                {
                    DB::table('beautyfortstocks_new')->insert($t);
                }
            }
            return response()->json('success', 200);
        } catch (ServerException $exception) {
            $res = $exception->getResponse()->getBody()->getContents();
            return response()->json($res, 500);
        }
    }
    public function filternewoldstocks(Request $request)
    {
        Beautyfortnewstock::truncate();
        $sql = "SELECT n.* FROM beautyfortstocks_new AS n LEFT JOIN beautyfortstocks_old AS o ON n.stock_code = o.stock_code WHERE o.stock_code IS NULL";
        $newstocks = DB::select(DB::raw($sql));
        $newstocks = array_map(function ($value) {
            return (array)$value;
        }, $newstocks);
        DB::table('beautyfortnewstocks')->insert($newstocks);

        Beautyfortremovedstock::truncate();
        $sql = "SELECT o.* FROM beautyfortstocks_old AS o LEFT JOIN beautyfortstocks_new AS n ON n.stock_code = o.stock_code WHERE n.stock_code IS NULL";
        $removedstocks = DB::select(DB::raw($sql));
        $removedstocks = array_map(function ($value) {
            return (array)$value;
        }, $removedstocks);
        DB::table('beautyfortremovedstocks')->insert($removedstocks);

        echo 'done';
    }
}