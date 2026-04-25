<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Business;

class BusinessController extends Controller
{
    public function show(Business $business)
    {
        return view('store.business', compact('business'));
    }
}
