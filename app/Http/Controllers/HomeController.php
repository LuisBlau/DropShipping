<?php

namespace App\Http\Controllers;

use Session;
use Carbon;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Beautyfortstock;
use App\Models\Beautyfortstockold;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        switch(Auth::user()->role_id) {
            case 1:
                $all = Beautyfortstock::select(DB::raw('count(*) as `all`'))->get();
                $available = DB::select(DB::raw('SELECT COUNT(*) as `available` FROM beautyfortstocks_new WHERE stock_level>15'));
                $new = DB::select(DB::raw("SELECT COUNT(*) as `new` FROM beautyfortstocks_new AS n LEFT JOIN beautyfortstocks_old AS o ON n.stock_code = o.stock_code WHERE o.stock_code IS NULL"));

                $total = array();
                $total['all'] = number_format($all[0]['all'],0,'.',',');
                $total['available'] = number_format($available[0]->available,0,'.',',');
                $total['new'] = number_format($new[0]->new,0,'.',',');
                $total['old'] = number_format($all[0]['all']-$new[0]->new,0,'.',',');

                return view('dashboard.admin')
                    ->with('total', $total);
                break;
            default:
                break;
        }
    }
}
