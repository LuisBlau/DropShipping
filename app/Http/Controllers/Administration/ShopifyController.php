<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\ShopifySellerProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Ebaysellerproduct;

class ShopifyController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth:api');
    }
    public function getProducts(Request $request): JsonResponse
    {
        $columnsForOrder = ['id','title','product_type', 'tags', 'published_scope', 'image', 'status', 'sku', 'vendor', 'quantity', 'price', 'currency'];
        $draw = $request->draw;
        $order = $request->order;
        $start = $request->start;
        $length = $request->length;
        $search = $request->search;

        $sql = "SELECT id, title, product_type, tags, published_scope, image, status, sku, vendor, quantity, price, currency FROM shopify_seller_products";
        $count_sql = "SELECT COUNT(*) as cnt FROM shopify_seller_products";
        if($search['value']) {
            $sql .= " WHERE CONCAT(title, '', product_type, '', tags, '', vendor) LIKE '%".$search['value']."%'";
            $count_sql .= " WHERE CONCAT(title, '', product_type, '', tags, '', vendor) LIKE '%".$search['value']."%'";
        }
        foreach($order as $o) {
            $sql.=" ORDER BY ";
            $sql.= $columnsForOrder[$o['column']];
            $sql.=' ';
            $sql.=$o['dir'];
        }
        $sql .= " LIMIT $start, $length";
        $data = DB::select( DB::raw($sql) );
        $recordsFiltered = DB::select( DB::raw($count_sql) );
        $recordsFiltered = $recordsFiltered[0]->cnt;
        $recordsTotal = DB::table('shopify_seller_products')->count();
        $dt = array();
        foreach($data as $d) {
            $d = (array)$d;
            $d['image'] = '<img class="product-image" src="'.$d['image'].'"></img>';
            $dt[] = $d;
        }
        $result = array();
        $result['draw'] = intval($draw);
        $result['recordsTotal'] = $recordsTotal;
        $result['recordsFiltered'] = $recordsFiltered;
        $result['data'] = $dt;
        return response()->json($result);
    }

    public function getRemovedProducts(Request $request): JsonResponse
    {
        $columnsForOrder = ['id','title','product_type', 'tags', 'published_scope', 'image', 'status', 'sku', 'vendor', 'quantity', 'price', 'currency'];
        $draw = $request->draw;
        $order = $request->order;
        $start = $request->start;
        $length = $request->length;
        $search = $request->search;

        $sql = "SELECT id, title, product_type, tags, published_scope, image, status, sku, vendor, quantity, price, currency FROM shopify_removed_products";
        $count_sql = "SELECT COUNT(*) as cnt FROM shopify_removed_products";
        if($search['value']) {
            $sql .= " WHERE CONCAT(title, '', product_type, '', tags, '', vendor) LIKE '%".$search['value']."%'";
            $count_sql .= " WHERE CONCAT(title, '', product_type, '', tags, '', vendor) LIKE '%".$search['value']."%'";
        }
        foreach($order as $o) {
            $sql.=" ORDER BY ";
            $sql.= $columnsForOrder[$o['column']];
            $sql.=' ';
            $sql.=$o['dir'];
        }
        $sql .= " LIMIT $start, $length";
        $data = DB::select( DB::raw($sql) );
        $recordsFiltered = DB::select( DB::raw($count_sql) );
        $recordsFiltered = $recordsFiltered[0]->cnt;
        $recordsTotal = DB::table('shopify_removed_products')->count();
        $dt = array();
        foreach($data as $d) {
            $d = (array)$d;
            $d['image'] = '<img class="product-image" src="'.$d['image'].'"></img>';
            $dt[] = $d;
        }
        $result = array();
        $result['draw'] = intval($draw);
        $result['recordsTotal'] = $recordsTotal;
        $result['recordsFiltered'] = $recordsFiltered;
        $result['data'] = $dt;
        return response()->json($result);
    }

    public function getUploadedProducts(Request $request): JsonResponse
    {
        $columnsForOrder = ['id','title','product_type', 'tags', 'published_scope', 'image', 'status', 'sku', 'vendor', 'quantity', 'price', 'currency'];
        $draw = $request->draw;
        $order = $request->order;
        $start = $request->start;
        $length = $request->length;
        $search = $request->search;

        $sql = "SELECT id, title, product_type, tags, published_scope, image, status, sku, vendor, quantity, price, currency FROM shopify_uploaded_products";
        $count_sql = "SELECT COUNT(*) as cnt FROM shopify_uploaded_products";
        if($search['value']) {
            $sql .= " WHERE CONCAT(title, '', product_type, '', tags, '', vendor) LIKE '%".$search['value']."%'";
            $count_sql .= " WHERE CONCAT(title, '', product_type, '', tags, '', vendor) LIKE '%".$search['value']."%'";
        }
        foreach($order as $o) {
            $sql.=" ORDER BY ";
            $sql.= $columnsForOrder[$o['column']];
            $sql.=' ';
            $sql.=$o['dir'];
        }
        $sql .= " LIMIT $start, $length";
        $data = DB::select( DB::raw($sql) );
        $recordsFiltered = DB::select( DB::raw($count_sql) );
        $recordsFiltered = $recordsFiltered[0]->cnt;
        $recordsTotal = DB::table('shopify_uploaded_products')->count();
        $dt = array();
        foreach($data as $d) {
            $d = (array)$d;
            $d['image'] = '<img class="product-image" src="'.$d['image'].'"></img>';
            $dt[] = $d;
        }
        $result = array();
        $result['draw'] = intval($draw);
        $result['recordsTotal'] = $recordsTotal;
        $result['recordsFiltered'] = $recordsFiltered;
        $result['data'] = $dt;
        return response()->json($result);
    }
}
