<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Barber;

class BarberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barbers = Barber::all();

        return response()->json($barbers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $customer=new Customer;
        // $customer->first_name=$request->first_name;
        // $customer->last_name=$request->last_name;
        // $customer->email=$request->email;
        // $customer->save();
        // return response()->json($customer);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $customer=Customer::findOrFail($id);
        // return response()->json($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $customer=Customer::findOrFail($id);
        // $customer->first_name=$request->first_name;
        // $customer->last_name=$request->last_name;
        // $customer->email=$request->email;
        // $customer->save();
        // return response()->json(['message' => 'Customer Updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $customer=Customer::findOrFail($id);
        // $customer->delete();
        // return response()->json(['message' => 'Customer Deleted']);

    }
}
