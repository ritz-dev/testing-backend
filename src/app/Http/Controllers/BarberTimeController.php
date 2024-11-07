<?php

namespace App\Http\Controllers;

use App\Models\Time;
use App\Models\BarberTime;
use Illuminate\Http\Request;

class BarberTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $times = BarberTime::where('barber_id', $request->id)->get();
        foreach($times as $time) {
            if (date('N', strtotime($request->date)) == $time->working_day) {
                $from = $time->from == '00:00' ? '12:00' : $time->from;
                $from = date('H:i', strtotime('-1 minute', strtotime($from)));
                $to = $time->to;
                $available_time = Time::whereBetween('time_period', [$from, $to])->select('uniqueid', 'time_period')->get();

                return response()->json($available_time);
            }
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BarberTime $barberTime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarberTime $barberTime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarberTime $barberTime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarberTime $barberTime)
    {
        //
    }
}
