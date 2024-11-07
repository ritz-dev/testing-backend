<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barber;
use App\Models\Walkin;
use App\Models\Service;
use App\Models\AuditLog;
use App\Models\BookingTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WalkInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $details = Walkin::leftJoin('barber', 'barber.uniqueid', 'walk_in_customers.barber_id')
                            ->leftJoin('service', 'service.uniqueid', 'walk_in_customers.service_id')
                            ->leftJoin('booking_time', 'booking_time.uniqueid', 'walk_in_customers.time_period_id')
                            ->select('walk_in_customers.uniqueid','walk_in_customers.name','walk_in_customers.phone', 'barber.barber_name AS barber_name', 'walk_in_customers.date', 'walk_in_customers.discount', 'walk_in_customers.discount_type', 'booking_time.time_period AS start_time', 'service.service_name', 'service.duration','service.pricing', 'walk_in_customers.status')
                            ->orderBy('walk_in_customers.created_at', 'desc')
                            ->get();

    $modifiedDetails = [];

    foreach ($details as $detail) {
        $uniqueid = $detail->uniqueid;

        if (isset($modifiedDetails[$uniqueid])) {
            // Combine service names
            // $modifiedDetails[$uniqueid]->service_name .= ' + ' . $detail->service_name;
            $modifiedDetails[$uniqueid]->service_name .= ' + ' . $detail->service_name;
            $modifiedDetails[$uniqueid]->pricing += $detail->pricing;

            // Calculate total duration
            $modifiedDetails[$uniqueid]->duration += $detail->duration;
            $carbonStartTime = Carbon::parse($modifiedDetails[$uniqueid]->start_time);
            $modifiedDetails[$uniqueid]->end_time =  $carbonStartTime->addMinutes($modifiedDetails[$uniqueid]->duration)->format('H:i');
        } else {
            $modifiedDetails[$uniqueid] = $detail;
            $carbonStartTime = Carbon::parse($modifiedDetails[$uniqueid]->start_time);
            $modifiedDetails[$uniqueid]->end_time =  $carbonStartTime->addMinutes($modifiedDetails[$uniqueid]->duration)->format('H:i');
        }
    }
    $details = array_values($modifiedDetails);

    return view('walk_in.index', compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barbers = Barber::select('uniqueid', 'barber_name')->get();
        $services = Service::all();

        return view('walk_in.create', compact('barbers', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        $services = Service::select('uniqueid')->get();
        $unid=UniqueIDController::generateUniqueID('walk_in_customers');
        $wic = 0;
        foreach ($services as $service) {
            if (array_key_exists($service->uniqueid, $requestData)) {
                $pricing = Service::where('uniqueid',$service->uniqueid)->pluck('pricing')->first();
                Walkin::insert([
                    'uniqueid' => $unid,
                    'barber_id' => $request->barber_id,
                    'service_id' => $service->uniqueid,
                    'date' => $request->date,
                    'time_period_id' => $request->start_time_id,
                    'status' => 'complete',
		            'name' => $request->name,
                    'phone' => $request->phone != "" ? $request->phone : "",
                    'amount' => $pricing,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ]);
                $wic++;
            }else{
                continue;
            }
        }

        if($wic != 0){
            return redirect('walk_in')->with("success", 'Walk In Saved Successfully');
        }else{
            return redirect('walk_in/create')->with("error", 'Something went wrong! Try Again');
        }
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
        $barbers = Barber::select('uniqueid', 'barber_name')->get();
        $time_periods = BookingTime::get();
        $services = Service::get();
        $wic = Walkin::where('uniqueid',$id)->get();
        return view('walk_in.edit', compact('wic','barbers','time_periods','services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requestData = $request->all();
        $services = Service::select('uniqueid')->get();
        Walkin::where('uniqueid', $id)->delete();
        $wic = 0;

        foreach ($services as $service) {
            if (array_key_exists($service->uniqueid, $requestData)) {
                Walkin::insert([
                    'uniqueid' => $id,
                    'barber_id' => $request->barber_id,
                    'service_id' => $service->uniqueid,
                    'discount_type' => $request->discount_type,
                    'discount' => $request->discount,
                    'date' => $request->date,
                    'time_period_id' => $request->start_time_id,
                    'status' => 'complete',
		            'name' => $request->name,
                    'phone' => $request->phone != "" ? $request->phone : "",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $user = Auth::user();

                AuditLog::create([
                    'customer_id' => "walkincustomer",
                    'staff' => $user->id,
                    'status' => "complete",
                    'service' => $service->uniqueid,
                    'discount' => $request->discount,
                    'discount_type' => $request->discount_type
                ]);


                $wic++;
            } else {
                continue;
            }
        }

        // $user = Auth::user();
        // if ($request->has('services') && is_array($request->services)) {
        //     foreach($request->services as $service) {
        //         AuditLog::create([
        //             'customer_id' => "walkincustomer",
        //             'staff' => $user->id,
        //             'status' => "complete",
        //             'service' => $service,
        //             'discount' => $request->discount,
        //             'discount_type' => $request->discount_type
        //         ]);
        //     }
        // }
        if ($wic != 0) {
            return redirect('walk_in')->with("success", 'Walk In Updated Successfully');
        } else {
            return redirect('walk_in/create')->with("error", 'Something went wrong! Try again');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Walkin::where('uniqueid',$id)->delete();
        if($delete){
            return redirect('walk_in')->with("success", 'Deleted Successfully');
        }else{
            return redirect('walk_in')->with("error", 'Something went wrong! Try again');
        }
    }
}
