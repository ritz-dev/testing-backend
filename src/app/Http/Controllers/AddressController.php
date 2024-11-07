<?php

namespace App\Http\Controllers;

use App\Models\ShopAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function getAddress()
    {
        $address = ShopAddress::first();
        return response()->json(['status' => 200, 'message' => 'Success', 'data' => $address]);
    }
}
