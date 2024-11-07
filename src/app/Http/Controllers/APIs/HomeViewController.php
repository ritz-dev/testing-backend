<?php

namespace App\Http\Controllers\APIs;

use App\Models\HomeView;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeViewController extends Controller
{
    public function home(){
        $home_views = HomeView::get();
        return response()->json(
            $home_views,
        );
    }
}
