<?php

namespace App\Http\Controllers\Administration;

use Session;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\Ebaysellerproduct;

class EbayController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth:api');
    }
    public function allproducts(Request $request)
    {
        $sql = "SELECT n.* FROM beautyfortstocks_new AS n LEFT JOIN beautyfortstocks_old AS o ON n.stock_code = o.stock_code WHERE o.stock_code IS NULL";
        $dt = DB::select(DB::raw($sql));
        
        $columns = ['id','title','itemid','startprice','currency','quantity','quantitysold','country'];
        $draw = $request->draw;
        $order = $request->order;
        $start = $request->start;
        $length = $request->length;
        $search = $request->search;

        $sql = "SELECT ".implode(',', $columns)." FROM ebaysellerproducts";
        $count_sql = "SELECT COUNT(*) as cnt FROM ebaysellerproducts";
        if($search['value']) {
            $sql .= " WHERE CONCAT(`title`,'',`itemid`,'',`startprice`,'', `currency`,'',quantity,'',quantitysold,'',country) LIKE '%".$search['value']."%'";
            $count_sql .= " WHERE CONCAT(`title`,'',`itemid`,'',`startprice`,'', `currency`,'',quantity,'',quantitysold,'',country) LIKE '%".$search['value']."%'";
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
        $recordsTotal = DB::table('ebaysellerproducts')->count();
        $dt = array();
        foreach($data as $d) {
            $d = (array)$d;
            $dt[] = array_values($d);
        }
        $result = array();
        $result['draw'] = intval($draw);
        $result['recordsTotal'] = $recordsTotal;
        $result['recordsFiltered'] = $recordsFiltered;
        $result['data'] = $dt;
        return response()->json($result);
    }
}