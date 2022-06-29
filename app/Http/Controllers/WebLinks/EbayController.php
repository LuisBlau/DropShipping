<?php

namespace App\Http\Controllers\WebLinks;

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
    public function getebaysellerproducts()
    {
        $products = Ebaysellerproduct::all();
        return view('ebay.allproducts')
            ->with('products', $products);
    }
    public function getebayuploadeditems()
    {
        $products = Ebaysellerproduct::all();
        return view('ebay.uploadeditems')
            ->with('products', $products);
    }
    public function getebayremoveditems()
    {
        $products = Ebaysellerproduct::all();
        return view('ebay.removeditems')
            ->with('products', $products);
    }
}