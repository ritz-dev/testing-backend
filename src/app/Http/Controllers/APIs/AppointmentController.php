<?php

namespace App\Http\Controllers\APIs;

use Carbon\Carbon;
use App\Models\Customer;
use App\Models\BarberTime;
use Illuminate\Http\Request;
use App\Models\BarberCommission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
    public function update(Request $request)
    {
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

    public function check(Request $request)
{
    if (!$request->date) {
        return response()->json([]);
    }

    $date = date('Y-m-d', strtotime($request->date));
    $barber = $request->barber;

    // Check barber absence
    $absence_dates = DB::table('barber_absence')->where('barber_id', $barber)->where('time_period_id', null)->get();
    // $absence_dates = DB::table('barber_absence')->where('barber_id', $barber)->where('time_period_id', null)->get();

    foreach ($absence_dates as $absence_date) {
        if ($date === date('Y-m-d', strtotime($absence_date->day))) {
            return response()->json([]);
        }
    }

    //$absence_dates = DB::table('barber_absence')->where('barber_id', $barber)->where('day', $date)->whereNot('time_period_id', null)->first();


    // Check barber working days
    $working_days = DB::table('barber')->where('uniqueid', $barber)->get();

    foreach ($working_days as $day) {
        $work_days = explode(",", $day->working_days);
        if (!in_array(date('N', strtotime($request->date)), $work_days)) {
            return response()->json([]);
        }
    }

    $all_time_periods = DB::table('booking_time')->select('uniqueid', 'time_period')->get();
    $unavailable_time = [];

    // Get booked time and service durations
    $booked_time = DB::table('booking')
        ->where('barber_id', $barber)
        ->where('date', $date)
        ->where('status', 'active')
        ->get();

    foreach ($booked_time as $bt) {

        $service_durations = DB::table('booking')
            ->join('service', 'booking.service_id', '=', 'service.uniqueid')
            ->where('booking.uniqueid', $bt->uniqueid)
            ->pluck('service.duration')
            ->toArray();

        // Calculate the total service duration
        $total_duration = array_sum($service_durations);

        //Calculate the previous time
        $previous_time_periods = DB::table('booking_time')
        ->where('id', '<', function ($query) use ($bt) {
            $query->select('id')
                ->from('booking_time')
                ->where('uniqueid', $bt->time_period_id)
                ->limit(1);
        })
        ->orderByDesc('id')
        ->take(3)
        ->pluck('time_period')
        ->toArray();

        // Get the start time of the booking
        $start_time = DB::table('booking_time')
            ->where('uniqueid', $bt->time_period_id)
            ->value('time_period');
        // Create a Carbon instance for the start time
        $carbonStartTime = Carbon::parse($start_time);

        // Add the total service duration in minutes to the start time
        $end_time = $carbonStartTime->addMinutes($total_duration)->format('H:i');
        $combined_time_periods = array_merge($previous_time_periods, [$start_time]);

        array_push($unavailable_time, ['time_periods' => $combined_time_periods, 'end_time' => $end_time]);
        // array_push($unavailable_time, ['start_time' => $start_time, 'end_time' => $end_time]);
    }

    $query = DB::table('booking_time');

    // foreach ($unavailable_time as $timeRange) {
    //     $query->whereNotBetween('time_period', [$timeRange['start_time'], $timeRange['end_time']]);
    // }



    foreach ($unavailable_time as $timeRange) {

        // $query->whereNotBetween('time_period', [$timeRange['time_periods'][0], $timeRange['end_time']]);
        $hidden_duration = 15;
        $adjusted_start_time = Carbon::parse($timeRange['time_periods'][0])->subMinutes($hidden_duration)->format('H:i');
        $timeRange['time_periods'][0] = $adjusted_start_time;
        $query->whereNotBetween('time_period', [$adjusted_start_time, $timeRange['end_time']]);
    }

    $absence_time = DB::table('barber_absence')->where('barber_id', $barber)->where('day', $date)->pluck('time_period_id')->first();

    if($absence_time)
    {
        $update_absence_time = Carbon::parse($absence_time)->subMinutes(30);
        $query->where(DB::raw("STR_TO_DATE(time_period, '%H:%i')"), '<', $update_absence_time);
    }

    $times = BarberTime::where('barber_id', $barber)->get();
    foreach($times as $time) {
        if (date('N', strtotime($request->date)) == $time->working_day) {
            $from = $time->from == '00:00' ? '12:00' : $time->from;
            $from = date('H:i', strtotime('-1 minute', strtotime($from)));
            $to = $time->to;

            $available_time = $query->whereBetween('time_period', [$from, $to])->select('uniqueid', 'time_period')->get();

            return response()->json($available_time);
        }
    }
}


    public function booking_summary(Request $request)
    {
        $barberId = 'c6uosnwR'; //$request->barber_id;
        $serviceId = ['BS2mJTUF', 'BS3mGGPO']; //$request->service_id;
        $date = '2023-05-19'; //$request->date;
        $start_timeId = 'JqLCSnLq'; //$request->start_time;
        $customerId = 'g1ZgInJJ'; //$request->customer_id;

        $barber_details = DB::table('barber')->where('uniqueid', $barberId)->select('uniqueid', 'barber_name', 'barber_photo')->get();
        $customer_details = DB::table('customer')->where('uniqueid', $customerId)->select('uniqueid', 'name', 'email')->get();
        $services_details=array();
        $total_duration=0;
        $total_price=0;
        foreach($serviceId as $service){
            $service_info=DB::table('service')->where('uniqueid',$service)->first();
            array_push($services_details,$service_info);
            $total_duration += $service_info->duration;
            $total_price += $service_info->pricing;
        }

        // $total_period_take = $service_duration / 15;
        $startTime = DB::table('booking_time')->where('uniqueid', $start_timeId)->first()->time_period;

        // Create a Carbon instance for the start time
        $carbonStartTime = Carbon::parse($startTime);

        // Add the service duration in minutes to the start time
        $endTime = $carbonStartTime->addMinutes($total_duration)->format('H:i');

        return response()->json([
            'barber_info' => $barber_details,
            'customer_info' => $customer_details,
            'booking_date' => $date,
            'services_info' => $services_details,
            'total_duration' => $total_duration,
            'total_price' => $total_price,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }
	public function barberCommission(Request $request)
    {

            $commission_rate = new BarberCommission;
            $commission_rate->barber_id = $request->barber_id;
            $commission_rate->date = $request->date;
            $commission_rate->commission_rate = $request->commission_rate;
            $commission_rate->save();



            return response()->json([
                "message" => "success",
                "data" => $commission_rate->commission_rate,
            ]);

    }

	public function barberCommissionDelete(Request $request){

        $month = Carbon::parse($request->date);

        $commission_rate = BarberCommission::where('barber_id',$request->barber_id)
                                            ->whereMonth('date','=',$month)
                                            ->where('commission_rate','=',$request->commission_rate)
                                            ->delete();
        return response()->json([
            "message" => "success",
        ]);
    }

}
