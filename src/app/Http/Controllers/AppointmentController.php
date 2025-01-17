<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use App\Rules\Discount;
use App\Models\AuditLog;
use App\Models\Customers;
use App\Models\BookingTime;
use Illuminate\Http\Request;
use App\Models\BarberCommission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $currentDate = Carbon::now()->format('Y-m-d');
        $previousMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $startDate = $request->input('start_date', $previousMonth);
        $endDate = $request->input('end_date', $currentDate);

        if($request->input('start_date') && $request->input('end_date')){
            $appointments = Booking::leftJoin('service', 'service.uniqueid', '=', 'booking.service_id')
                            ->leftJoin('customer', 'customer.uniqueid', '=', 'booking.customer_id')
                            ->leftJoin('barber', 'barber.uniqueid', '=', 'booking.barber_id')
                            ->leftJoin('booking_time', 'booking_time.uniqueid', '=', 'booking.time_period_id')
                            ->select('booking.*', 'booking.uniqueid as booking_unid', 'service.*','barber.*', 'customer.*', 'booking_time.*')
                            ->whereBetween('date', [$startDate, $endDate])
                            ->orderBy('booking.date', 'desc')
                            ->orderBy('booking_time.time_period', 'asc')
                            ->get();
        }else{
            $appointments = Booking::leftJoin('service', 'service.uniqueid', '=', 'booking.service_id')
                            ->leftJoin('customer', 'customer.uniqueid', '=', 'booking.customer_id')
                            ->leftJoin('barber', 'barber.uniqueid', '=', 'booking.barber_id')
                            ->leftJoin('booking_time', 'booking_time.uniqueid', '=', 'booking.time_period_id')
                            ->select('booking.*', 'booking.uniqueid as booking_unid', 'service.*','barber.*', 'customer.*', 'booking_time.*')
                            //->whereBetween('date', [$startDate, $endDate])
                            ->orderBy('booking.date', 'desc')
                            ->orderBy('booking_time.time_period', 'asc')
                            ->get();


        }

        $modifiedAppointments = [];

        foreach ($appointments as $appointment) {
            $bookingUnid = $appointment->booking_unid;

            if (isset($modifiedAppointments[$bookingUnid])) {
                // Combine service names
                $modifiedAppointments[$bookingUnid]->service_name .= ' + ' . $appointment->service_name;
                $modifiedAppointments[$bookingUnid]->amount += $appointment->amount;

                // Calculate total duration
                $modifiedAppointments[$bookingUnid]->duration += $appointment->duration;
                $carbonStartTime = Carbon::parse($modifiedAppointments[$bookingUnid]->time_period);
                $modifiedAppointments[$bookingUnid]->end_time =  $carbonStartTime->addMinutes($modifiedAppointments[$bookingUnid]->duration)->format('H:i');
            } else {
                $modifiedAppointments[$bookingUnid] = $appointment;
                $carbonStartTime = Carbon::parse($modifiedAppointments[$bookingUnid]->time_period);
                $modifiedAppointments[$bookingUnid]->end_time =  $carbonStartTime->addMinutes($modifiedAppointments[$bookingUnid]->duration)->format('H:i');
            }
        }
        // dd($modifiedAppointments);
        // Convert the modified appointments to a simple array
        $appointments = array_values($modifiedAppointments);
        // dd($appointments);
        return view('appointments.index', compact('appointments', 'startDate', 'endDate'));

    }

	public function customerAjax(Request $request){
           $customer_id = $request->customerId;
           $customer = Customers::where('id',$customer_id)->first();
           return response()->json([
             'phone' => $customer->contact_number,
         ]);
    }

public function customerPaginationAjax(Request $request){
        $query = Customers::query();

        if($request->has('q')) {
            $search = $request->q;
            $query->where('name', 'LIKE', "%$search%");
        }

        $page = $request->has('page') ? (int) $request->page : 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $results = $query->select('id', 'name')->offset($offset)->limit($perPage)->get();
        return response()->json($results);
    }

    public function changeStatus(Request $request)
    {
        $booking = DB::table('booking')->where('uniqueid', $request->booking_unid)->update(['status' => $request->status]);
        return response()->json($request->status);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barbers = Barber::all();
        $services = Service::all();
        $customers = Customers::get();
        return view('appointments.create', compact('barbers', 'services','customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barber' => 'required',
            'date' => 'required',
            'time_period' => 'required',
            'discount_type' => 'required',
            'discount' => ['min:0','required_if:discount_type,barber,shop',new Discount],
            'services' => 'required'
        ]);
        $uniqueid = UniqueIDController::generateUniqueID('booking');
        foreach($request->services as $service) {
            $pricing = Service::where('uniqueid',$service)->pluck('pricing')->first();
            $customer = Customers::where('id',$request->customer_id)->first();
            Booking::insert([
                'uniqueid' => $uniqueid,
                'barber_id' => $request->barber,
                'service_id' => $service,
                'date' => $request->date,
                'discount_type' => $request->discount_type,
                'discount' => $request->discount,
                'time_period_id' => $request->time_period,
                'type' => 'by_phone',
                'customer_id' => $customer->uniqueid,
                'contact_name' => $customer->name,
                'contact_phone' => $customer->contact_number,
                'amount' => $pricing,
            ]);

        }

        return redirect()->route('booking.index')->with('success', 'Successfully added an appointment.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $appointments = Booking::leftJoin('service', 'service.uniqueid', '=', 'booking.service_id')
                            ->leftJoin('customer', 'customer.uniqueid', '=', 'booking.customer_id')
                            ->leftJoin('barber', 'barber.uniqueid', '=', 'booking.barber_id')
                            ->leftJoin('booking_time', 'booking_time.uniqueid', '=', 'booking.time_period_id')
                            ->where('booking.uniqueid', $id)
                            ->select('booking.uniqueid as booking_unid', 'service.uniqueid as service_unid', 'barber.uniqueid as barber_unid', 'booking_time.uniqueid as time_unid', 'booking.*', 'service.*', 'customer.*', 'barber.*', 'booking_time.*')
                            ->get();
        $modifiedAppointments = [];

        $services = array();

        foreach ($appointments as $appointment) {
            $bookingUnid = $appointment->booking_unid;

            if (isset($modifiedAppointments[$bookingUnid])) {
                // Combine service names
                array_push($services, $appointment->service_unid);
                // $modifiedAppointments[$bookingUnid]->pricing += $appointment->pricing;

                // Calculate total duration
                $modifiedAppointments[$bookingUnid]->duration += $appointment->duration;
                $carbonStartTime = Carbon::parse($modifiedAppointments[$bookingUnid]->time_period);
                $modifiedAppointments[$bookingUnid]->end_time =  $carbonStartTime->addMinutes($modifiedAppointments[$bookingUnid]->duration)->format('H:i');
            } else {
                array_push($services, $appointment->service_unid);

                $modifiedAppointments[$bookingUnid] = $appointment;
                $carbonStartTime = Carbon::parse($modifiedAppointments[$bookingUnid]->time_period);
                $modifiedAppointments[$bookingUnid]->end_time =  $carbonStartTime->addMinutes($modifiedAppointments[$bookingUnid]->duration)->format('H:i');
            }
        }

        $modifiedAppointments[$bookingUnid]->services = $services;
        // dd($services);
        // Convert the modified appointments to a simple array
        $appointment = array_values($modifiedAppointments);
        $appointment = $appointment[0];
        // dd($appointment);

        $barbers = Barber::get();
        $services = Service::get();
        $time_periods = BookingTime::select('uniqueid', 'time_period')->get();

        $unavailable_time = array();
        if (Booking::where('barber_id', $appointment->barber_unid)->where('date', $appointment->date)->get()) {
            $booked_time = Booking::where('barber_id', $appointment->barber_unid)->where('date', $appointment->date)->where('uniqueid', '!=', $id)->get(); //getting booking records

            foreach ($booked_time as $bt) { //each booking record
                $unavailable_start_time = Booking::where('uniqueid', $bt->uniqueid)->select('service_id', 'time_period_id')->get();

                foreach ($unavailable_start_time as $ust) {
                    $service_duration = Service::where('uniqueid', $ust->service_id)->first()->duration;
                    // $total_period_take = $service_duration / 15;
                    $startTime = BookingTime::where('uniqueid', $ust->time_period_id)->first()->time_period;

                    // Create a Carbon instance for the start time
                    $carbonStartTime = Carbon::parse($startTime);

                    // Add the service duration in minutes to the start time
                    $endTime = $carbonStartTime->addMinutes($service_duration)->format('H:i');
                    array_push($unavailable_time, ['start_time' => $startTime, 'end_time' => $endTime]);
                }
            }

            $query = DB::table('booking_time');

            foreach ($unavailable_time as $timeRange) {
                $query->whereNotBetween('time_period', [$timeRange['start_time'], $timeRange['end_time']]);
                // $query->where('time_period', '>', $timeRange['start_time'])
                //     ->where('time_period', '<=', $timeRange['end_time']);
            }

            $time_periods = $query->select('uniqueid', 'time_period')->get();
        }

        // dd($appointment);

        return view('appointments.edit', compact('appointment', 'barbers', 'time_periods', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'discount_type' => 'required',
            'discount' => ['min:0','required_if:discount_type,barber,shop',new Discount],
        ]);
        $old_info =  Booking::where('uniqueid', $request->booking)->first();
        Booking::where('uniqueid', $request->booking)->delete();
        $uniqueid = UniqueIDController::generateUniqueID('booking');
        foreach($request->services as $service) {
            $pricing = Service::where('uniqueid',$service)->pluck('pricing')->first();
            if($old_info->type === "by_customer") {
                Booking::insert([
                    'uniqueid' => $uniqueid,
                    'customer_id' => $old_info->customer_id,
                    'barber_id' => $request->barber,
                    'service_id' => $service,
                    'discount_type' => $request->discount_type,
                    'discount' => $request->discount,
                    'date' => $request->date,
                    'time_period_id' => $request->time_period,
                    'status' => $old_info->status,
                    'amount' => $pricing,
                    'created_at' => $old_info->created_at
                ]);
            } else {
                Booking::insert([
                    'uniqueid' => $uniqueid,
                    'barber_id' => $request->barber,
                    'service_id' => $service,
                    'discount_type' => $request->discount_type,
                    'discount' => $request->discount,
                    'date' => $request->date,
                    'time_period_id' => $request->time_period,
                    'status' => $old_info->status,
                    'type' => $old_info->type,
                    'contact_name' => $old_info->contact_name,
                    'contact_phone' => $old_info->contact_phone,
                    'amount' => $pricing,
                    'created_at' => $old_info->created_at
                ]);
            }
        }
        $user = Auth::user();
        foreach($request->services as $service) {
                AuditLog::create([
                    'customer_id' => $old_info->customer_id,
                    'staff' => $user->id,
                    'status' => $old_info->status,
                    'service' => $service,
                    'discount' => $request->discount,
                    'discount_type' => $request->discount_type
                ]);
            }

        return redirect()->route('booking.index')->with('success', 'Successfully updated an appointment.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Booking::where('uniqueid', $request->booking_id)->delete();

        return redirect()->back()->with('success', 'Successfully deleted.');
    }

    public function showCalendar(Request $request)
    {
        $barbers = Barber::get();
        $appointments = Booking::leftJoin('service', 'service.uniqueid', 'booking.service_id')
            ->leftJoin('customer', 'customer.uniqueid', 'booking.customer_id')
            ->leftJoin('booking_time', 'booking_time.uniqueid', 'booking.time_period_id')
            ->select(
                'booking.customer_id AS customer_unid',
                'customer.name AS customer_name',
                'customer.contact_number AS customer_ph',
                'booking.barber_id AS barber_unid',
                'booking.service_id AS service_unid',
                // 'service.service_name AS service_name',
                'booking.time_period_id AS start_time_unid',
                'booking_time.time_period AS start_time',
                'booking.date AS date',
                'booking.uniqueid as booking_unid'
            )
            ->where('booking.status', 'active')
            ->get();
        // dd($appointments);


        $appointments = $appointments->map(function ($appointment) {
            $appointment->serviceName = Service::where('uniqueid', $appointment->service_unid)->first()->service_name;
            $appointment->duration = Service::where('uniqueid', $appointment->service_unid)->first()->duration;

            return $appointment;
        });

        $modifiedAppointments = [];

        foreach ($appointments as $appointment) {
            $bookingUnid = $appointment->booking_unid;

            if (isset($modifiedAppointments[$bookingUnid])) {
                // Combine service names
                $modifiedAppointments[$bookingUnid]->serviceName .= ' + ' . $appointment->serviceName;

                // Calculate total duration
                $modifiedAppointments[$bookingUnid]->duration += $appointment->duration;
                $carbonStartTime = Carbon::parse($modifiedAppointments[$bookingUnid]->start_time);
                $modifiedAppointments[$bookingUnid]->end_time =  $carbonStartTime->addMinutes($modifiedAppointments[$bookingUnid]->duration)->format('H:i');
            } else {
                $modifiedAppointments[$bookingUnid] = $appointment;
                $carbonStartTime = Carbon::parse($modifiedAppointments[$bookingUnid]->start_time);
                $modifiedAppointments[$bookingUnid]->end_time =  $carbonStartTime->addMinutes($modifiedAppointments[$bookingUnid]->duration)->format('H:i');
            }
        }
        // dd($modifiedAppointments);
        // Convert the modified appointments to a simple array
        $appointments = array_values($modifiedAppointments);

        // dd($appointments);
        return view('calendar', compact('barbers', 'appointments'));
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
                "data" => $commission_rate,
            ]);

    }
}
