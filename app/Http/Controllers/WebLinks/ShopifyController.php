<?php

namespace App\Http\Controllers\WebLinks;

use App\Http\Controllers\Controller;
use App\Models\ShopifySellerProduct;
use Illuminate\Http\Request;

class ShopifyController extends Controller
{
    public function getAllProducts()
    {
        return view('shopify.allproducts');
    }

    public function getRemovedProducts()
    {
        return view('shopify.removeditems');
    }

    public function getUploadedProducts()
    {
        return view('shopify.uploadeditems');
    }
}
