<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class FacebookController extends Controller
{
    public function facebookLogin(Request $request)
    {
        $facebookId = $request->input('facebookId');
        $name = $request->input('username');
        $email = $request->input('email');
     //   $accessToken = $request->input('accessToken'); // Add this line

        $uniqueId = UniqueIDController::generateUniqueID('customer');
        $existingUser = DB::table('customer')
            ->where('facebook_id', $facebookId)
            ->first();

        if ($existingUser) {
            DB::table('customer')
                ->where('facebook_id', $facebookId)
                ->update([
                    'name' => $name,
                    'uniqueid'=>$uniqueId
                ]);
        } else {
            //$uniqueId = UniqueIDController::generateUniqueID('customer');
            DB::table('customer')->insert([
                'facebook_id' => $facebookId,
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
            'message' => 'Login with Facebook successful',
       //     'access_token' => $accessToken // Include the access token in the response
        ]);
    }

}
