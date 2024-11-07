<?php

namespace App\Http\Controllers\APIs;

use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UniqueIDController;

class CustomerController extends Controller
{
    public function register(Request $request)
    {
        // return response()->json($request);
        $validator = $request->validate([
            'email' => 'email|required|unique:customer,email',
            'username' => 'required',
            'contactNumber' => 'required|numeric|unique:customer,contact_number',
            // 'password' => 'required'
        ]);

        if ($validator) {
            DB::table('customer')->insert([
                'uniqueid' => UniqueIDController::generateUniqueID('customer'),
                'name' => $request->username,
                'email' => $request->email,
                'contact_number' => $request->contactNumber,
                'password' => null
            ]);
            return response()->json(["status" => 200, "message" => "Successfully created a customer."]);
        } else {
            return response()->json($validator);
        }
    }

    public function detail(Request $request)
    {   
        $customer = DB::table('customer')->where('uniqueid', $request->customer_id)->select('id', 'name', 'email', 'dob', 'contact_number')->first();
        return response()->json(["status" => 200, 'message', 'success', "data" => $customer]);
    }

    public function login(Request $request)
{
    $validator = $request->validate([
        'identifier' => 'required',
        'username' => 'required'
    ]);


    if ($validator) {
        $check = DB::table('customer')
            ->where('name', $request->username)
            ->where(function ($query) use ($request) {
                $query->where('email', $request->identifier)
                      ->orWhere('contact_number', $request->identifier);
            })
            ->first();

        if ($check) {
            return response()->json(['status' => 200, 'message' => 'Login success.', 'data' => $check->uniqueid]);
        } else {
            return response()->json(['status' => 400, 'message' => 'Invalid credentials.']);
        }
    }
}

    public function gmail(Request $request)
{
        $googleId = $request->input('googleId');
        $name = $request->input('username');
        $email = $request->input('email');

        $uniqueId = UniqueIDController::generateUniqueID('customer');
        $existingUser = DB::table('customer')
            ->where('google_id', $googleId)
            ->first();

        if ($existingUser) {
            DB::table('customer')
                ->where('google_id', $googleId)
                ->update([
                    'name' => $name,
                    'uniqueid'=>$uniqueId
                ]);
        } else {
            //$uniqueId = UniqueIDController::generateUniqueID('customer');
            DB::table('customer')->insert([
                'google_id' => $googleId,
                'uniqueid' => $uniqueId,
                'name' => $name,
                'email' => $email,
                'contact_number' => "null",
                'password' => "null"
            ]);
        }

        return response()->json([
            'uniqueid' => $uniqueId,
            'name' => $name,
            'message' => 'Login with Google successful',
       //     'access_token' => $accessToken // Include the access token in the response
        ]);
}


    // public function login(Request $request)
    // {
    // $validator = $request->validate([
    //     'identifier' => 'required',
    //     'username' => 'required'
    // ]);

    // if ($validator) {
    //     $check = DB::table('customer')
    //         ->where('email', $request->identifier)
    //         ->orWhere('contact_number', $request->identifier)
    //         ->first();

    //     if ($check) {
    //         // if (Hash::check($request->password, $check->password)) {
    //             return response()->json(['status' => 200, 'message' => 'Login success.', 'data' => $check->uniqueid]);
    //         // } else {
    //         //     return response()->json(['status' => 400, 'message' => 'Password do not match.']);
    //         // }
    //     } else {
    //         return response()->json(['status' => 400, 'message' => 'User account not found.']);
    //     }
    // } else {
    //     return response()->json($validator);
    // }
    // }


    public function index()
    {
        $customers = Customers::all();
        return response()->json($customers);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:customer,email',
            'dob' => 'required',
            'contact_number' => 'required|string',
            'password' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator], 422);
        }

        $customer = new Customers();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->dob = $request->dob;
        $customer->contact_number = $request->contact_number;
        $customer->password = $request->password;
        $customer->created_at = now();
        $customer->save();

        return response()->json($customer);
    }

    public function update(Request $request, $id)
    {
        $customer = Customers::find($id);
        //dd($customer);
        //dd($request);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->contact_number = $request->contact_number;
        $customer->save();

        return response()->json($customer);
    }

    public function destroy($id)
    {
        $customer = Customers::find($id);
        $customer->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
