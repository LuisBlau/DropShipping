<?php

namespace App\Http\Controllers\WebLinks;

use Session;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\Beautyfortstock;

class BeautyfortController extends Controller
{
    public function getallproducts()
    {
        $stocks = Beautyfortstock::all();
        return view('beautyfort.allproducts')
            ->with('stocks', $stocks);
    }
    public function getnewstocks()
    {
        return view('beautyfort.newstocks');
    }
    public function getremovedproducts()
    {
        return view('beautyfort.removedstocks');
    }
    public function getavailablestocks()
    {
        $stocks = Beautyfortstock::all();
        return view('beautyfort.availablestocks')
            ->with('stocks', $stocks);
    }
}