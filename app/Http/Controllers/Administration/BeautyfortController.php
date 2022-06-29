<?php

namespace App\Http\Controllers\Administration;

use Session;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\Beautyfortstock;
use App\Models\Beautyfortnewstock;
use App\Models\Beautyfortremovedstock;

class BeautyfortController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth:api');
    }
    public function allproducts(Request $request)
    {
        $columns = ['id','stock_code','full_name','stock_level','rrp','price','last_purchased_price','barcode','collection','high_res_image_url','brand','quantity','type','size','gender','category'];
        $draw = $request->draw;
        $order = $request->order;
        $start = $request->start;
        $length = $request->length;
        $search = $request->search;

        $sql = "SELECT * FROM beautyfortstocks_new";
        $count_sql = "SELECT COUNT(*) as cnt FROM beautyfortstocks_new";
        if($search['value']) {
            $sql .= " WHERE CONCAT(`stock_code`,'',`full_name`,'',`stock_level`,'',COALESCE(`rrp`,''),'', `price`,'',COALESCE(`last_purchased_price`,''),'',COALESCE(`barcode`,''),'',COALESCE(`collection`,''),'',COALESCE(`high_res_image_url`,''),'',`brand`,'',COALESCE(`quantity`,''),'',`type`,'',COALESCE(`size`,''),'',`gender`,'',`category`) LIKE '%".$search['value']."%'";
            $count_sql .= " WHERE CONCAT(`stock_code`,'',`full_name`,'',`stock_level`,'',COALESCE(`rrp`,''),'', `price`,'',COALESCE(`last_purchased_price`,''),'',COALESCE(`barcode`,''),'',COALESCE(`collection`,''),'',COALESCE(`high_res_image_url`,''),'',`brand`,'',COALESCE(`quantity`,''),'',`type`,'',COALESCE(`size`,''),'',`gender`,'',`category`) LIKE '%".$search['value']."%'";
        }
        foreach($order as $o) {
            $sql.=" ORDER BY ";
            $sql.= $columns[$o['column']];
            $sql.=' ';
            $sql.=$o['dir'];
        }
        $sql .= " LIMIT $start, $length";
        //exit($sql);
        $data = DB::select( DB::raw($sql) );
        $recordsFiltered = DB::select( DB::raw($count_sql) );
        $recordsFiltered = $recordsFiltered[0]->cnt;
        $recordsTotal = DB::table('beautyfortstocks_new')->count();
        $dt = array();
        foreach($data as $d) {
            $d = (array)$d;
            $d['high_res_image_url'] = '<img class="product-image" src="'.$d['high_res_image_url'].'"></img>';
            //$d['created_at'] = niceShort($d['created_at']);
            $dt[] = array_values($d);
        }
        $result = array();
        $result['draw'] = intval($draw);
        $result['recordsTotal'] = $recordsTotal;
        $result['recordsFiltered'] = $recordsFiltered;
        $result['data'] = $dt;
        return response()->json($result);
    }
    public function availablestocks(Request $request)
    {
        $columns = ['stock_code','full_name','stock_level','rrp','price','last_purchased_price','barcode','collection','high_res_image_url','brand','quantity','type','size','gender','category','created_at'];
        $draw = $request->draw;
        $order = $request->order;
        $start = $request->start;
        $length = $request->length;
        $search = $request->search;

        $stocklimit = 15;
        $sql = "SELECT * FROM beautyfortstocks_new";
        $count_sql = "SELECT COUNT(*) as cnt FROM beautyfortstocks_new";
        if($search['value']) {
            $sql .= " WHERE stock_level>$stocklimit AND CONCAT(`stock_code`,'',`full_name`,'',`stock_level`,'',COALESCE(`rrp`,''),'', `price`,'',COALESCE(`last_purchased_price`,''),'',COALESCE(`barcode`,''),'',COALESCE(`collection`,''),'',COALESCE(`high_res_image_url`,''),'',`brand`,'',COALESCE(`quantity`,''),'',`type`,'',COALESCE(`size`,''),'',`gender`,'',`category`) LIKE '%".$search['value']."%'";
            $count_sql .= " WHERE stock_level>$stocklimit AND CONCAT(`stock_code`,'',`full_name`,'',`stock_level`,'',COALESCE(`rrp`,''),'', `price`,'',COALESCE(`last_purchased_price`,''),'',COALESCE(`barcode`,''),'',COALESCE(`collection`,''),'',COALESCE(`high_res_image_url`,''),'',`brand`,'',COALESCE(`quantity`,''),'',`type`,'',COALESCE(`size`,''),'',`gender`,'',`category`) LIKE '%".$search['value']."%'";
        } else {
            $sql .= " WHERE stock_level>$stocklimit";
            $count_sql .= " WHERE stock_level>$stocklimit";
        }
        foreach($order as $o) {
            $sql.=" ORDER BY ";
            $sql.= $columns[$o['column']];
            $sql.=' ';
            $sql.=$o['dir'];
        }
        $sql .= " LIMIT $start, $length";
        //exit($sql);
        $data = DB::select( DB::raw($sql) );
        $recordsFiltered = DB::select( DB::raw($count_sql) );
        $recordsFiltered = $recordsFiltered[0]->cnt;
        $recordsTotal = DB::table('beautyfortstocks_new')->count();
        $dt = array();
        for($i=0;$i<count($data);$i++) {
            $d = (array)$data[$i];
            $d['id'] = $i+1;
            $d['high_res_image_url'] = '<img class="product-image" src="'.$d['high_res_image_url'].'"></img>';
            //$d['created_at'] = niceShort($d['created_at']);
            $dt[] = array_values($d);
        }
        $result = array();
        $result['draw'] = intval($draw);
        $result['recordsTotal'] = $recordsTotal;
        $result['recordsFiltered'] = $recordsFiltered;
        $result['data'] = $dt;
        return response()->json($result);
    }
    public function newstocks(Request $request)
    {
        $data = array();
        $dt = Beautyfortnewstock::get()->toArray();
        foreach($dt as $d) {
            $d['high_res_image_url'] = '<img class="product-image" src="'.$d['high_res_image_url'].'"></img>';
            $data[] = array_values($d);
        }
        $result = array();
        $result['draw'] = 1;
        $result['recordsTotal'] = count($data);
        $result['recordsFiltered'] = count($data);
        $result['data'] = $data;
        return response()->json($result);
    }
    public function removedstocks(Request $request)
    {
        $data = array();
        $dt = Beautyfortremovedstock::get()->toArray();
        foreach($dt as $d) {
            $d['high_res_image_url'] = '<img class="product-image" src="'.$d['high_res_image_url'].'"></img>';
            $data[] = array_values($d);
        }
        $result = array();
        $result['draw'] = 1;
        $result['recordsTotal'] = count($data);
        $result['recordsFiltered'] = count($data);
        $result['data'] = $data;
        return response()->json($result);
    }
}