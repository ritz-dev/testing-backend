<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopAddress;
use App\Http\Requests\ShopAddressRequest;

class ShopAddressController extends Controller
{
    public function index()
    {
        $shopAddresses = ShopAddress::all();
        return view('shop_addresses.index', compact('shopAddresses'));
    }
    public function edit(string $id)
    {
        $shopAddress = ShopAddress::where('uniqueid', $id)->first();
        return view('shop_addresses.edit', compact('shopAddress'));
    }
    public function update(Request $request,$id)
    {
        $validatedData = $request->validate([
            'address' => 'required|string',
            'contact_number' => 'required|string'
        ]);
        ShopAddress::where('uniqueid', $id)->update([
            'address' => $request->address,
            'contact_number' => $request->contact_number
        ]);
    
        return redirect()->route('shop_addresses.index')->with('success', 'Shop address updated successfully!');
    }
}
