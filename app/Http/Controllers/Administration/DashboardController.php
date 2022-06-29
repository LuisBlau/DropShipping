<?php

namespace App\Http\Controllers\Administration;

use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth:api');
    }
    public function dashboardsmshourly(Request $request)
    {
        return response()->json(array());
    }
    public function dashboardsmsdaily(Request $request)
    {
        return response()->json(array());
    }
}