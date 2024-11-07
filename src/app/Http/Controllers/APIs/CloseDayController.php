<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\CloseDay;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CloseDayController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');

        $closeDays = CloseDay::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->get();

        return response()->json($closeDays);
    }
}
