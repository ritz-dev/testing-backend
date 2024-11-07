<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UniqueIDController extends Controller
{
    // Function to generate a random ID
    public static function generate()
    {
        $length = 8;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $randomID = '';

        for ($i = 0; $i < $length; $i++) {
            $randomID .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomID;
    }

    public static function generateUniqueID($dbname)
    {
        // Generate a random ID and check if it exists in the database
        do {
            $randomID = UniqueIDController::generate();
            $exists = DB::table($dbname)->where('uniqueid', $randomID)->exists();

            if (!$exists) {
                break;
            }
        } while (true);

        return $randomID;
    }
}
