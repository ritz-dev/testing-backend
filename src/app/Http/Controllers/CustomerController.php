<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Http\Controllers\UniqueIDController;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customers::get();


        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            //'email' => 'required|unique:customer',
            'contact_number' => 'required'
        ]);

        $customer = new Customers;
        $customer->uniqueid = UniqueIDController::generateUniqueID('customer');
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->contact_number = $request->contact_number;
        $customer->save();

        return redirect()->route('customer.index')->with('success', 'Successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = DB::table('customer')->where('uniqueid', $id)->first();

        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request);
        $request->validate([
            'customer_id' => 'required',
            'name' => 'required',
            //'email' => ['required', 'email', Rule::unique('customer')->ignore($request->customer_id, 'uniqueid')],
            'contact_number' => 'required'
        ]);

        DB::table('customer')->where('uniqueid', $request->customer_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number
        ]);

        return redirect()->route('customer.index')->with('success', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // DB::table('customer')->where('uniqueid', $request->customer_id)->delete();
        $customer = Customers::where('uniqueid',$request->customer_id)->first();
        $customer->delete();

        return redirect()->back()->with('success', 'Successfully deleted.');
    }
}
