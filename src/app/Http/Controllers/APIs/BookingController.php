<?php

namespace App\Http\Controllers\APIs;

use Carbon\Carbon;
use App\Models\Booking;
use App\Mail\BookingConfirm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\UniqueIDController;
use App\Mail\AppointmentCancel;
use App\Models\Time;
use App\Models\Service;

class BookingController extends Controller
{
    public function index()
    {
        $all_bookings = Booking::all();
        return response()->json($all_bookings);
    }

    public function bookingList(Request $request)
    {
        // $booking = Booking::where('customer_id',$request->customer_id)->get();
        $appointments = DB::table('booking')
            ->leftJoin('service', 'service.uniqueid', 'booking.service_id')
            ->leftJoin('customer', 'customer.uniqueid', 'booking.customer_id')
            ->leftJoin('booking_time', 'booking_time.uniqueid', 'booking.time_period_id')
            ->leftJoin('barber','barber.uniqueid','booking.barber_id')
            ->where('booking.customer_id',$request->customer_id)
            ->select(
                'booking.customer_id AS customer_unid',
                'customer.name AS customer_name',
                'customer.contact_number AS customer_ph',
                'booking.barber_id AS barber_unid',
                // 'booking.service_id AS service_unid',
                'booking.time_period_id AS start_time_unid',
                'booking_time.time_period AS start_time',
                'service.service_name AS serviceName',
                'service.duration AS duration',
                'service.pricing AS pricing',
                'booking.date AS date',
                'booking.uniqueid as booking_unid',
                'barber.barber_name AS barber_name',
                'booking.status AS status'
            )->get();

        // $appointments = $appointments->map(function ($appointment) {
        //     $appointment->serviceName = DB::table('service')->where('uniqueid', $appointment->service_unid)->first()->service_name;
        //     $appointment->duration = DB::table('service')->where('uniqueid', $appointment->service_unid)->first()->duration;

        //     return $appointment;
        // });

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
                $modifiedAppointments[$bookingUnid]->pricing += $appointment->pricing;
            } else {
                $modifiedAppointments[$bookingUnid] = $appointment;
                $carbonStartTime = Carbon::parse($modifiedAppointments[$bookingUnid]->start_time);
                $modifiedAppointments[$bookingUnid]->end_time =  $carbonStartTime->addMinutes($modifiedAppointments[$bookingUnid]->duration)->format('H:i');
                $modifiedAppointments[$bookingUnid]->pricing = $appointment->pricing;

            }
        }
        // dd($modifiedAppointments);
        // Convert the modified appointments to a simple array
        $appointments = array_values($modifiedAppointments);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $appointments]);
    }

    public function store(Request $request)
    {
        // return response()->json($request);
        $result = [];
        // DB::beginTransaction();
        // try {
            $services = $request->services;
            $uid = UniqueIDController::generateUniqueID('booking');
            foreach ($services as $service) {

                $service_pricing = Service::where('id',$service)
                                            ->pluck('pricing')
                                            ->firstOrFail();

                $booking = new Booking();
                $booking->uniqueid = $uid;
                $booking->customer_id = $request->customer;
                $booking->barber_id = $request->staff;
                $booking->service_id = $service;
                $booking->date = date('Y-m-d', strtotime($request->date));
                $booking->time_period_id = $request->time;
                $booking->status = 'active';
		        $booking->selected = $request->selected;
                $booking->amount = $service_pricing;
                $booking->save();

                array_push($result, $booking);
            }

            $booking_infos = DB::table('booking')
                ->leftJoin('customer', 'customer.uniqueid', '=', 'booking.customer_id')
                ->leftJoin('barber', 'barber.uniqueid', '=', 'booking.barber_id')
                ->leftJoin('service', 'service.uniqueid', '=', 'booking.service_id')
                ->leftJoin('booking_time', 'booking_time.uniqueid', '=', 'booking.time_period_id')
                ->where('booking.uniqueid', $uid)
                ->select('booking.*', 'customer.name as customer_name', 'barber.barber_name', 'service.service_name', 'service.duration', 'booking_time.time_period')
                ->first();

            $services = DB::table('booking')
                ->leftJoin('service', 'service.uniqueid', '=', 'booking.service_id')
                ->where('booking.uniqueid', $uid)
                ->select('service.*')
                ->get();

            $data = [
                'subject' => 'Thanks! Your appointment is confirmed at NOT Barber Shop',
                'body' => 'Your booking has been confirmed.',
                'booking_infos' => $booking_infos,
                'services' => $services,
                // 'website' => 'u-booking.com'
            ];
            // return response()->json($data);

            $customerEmail = DB::table('customer')->where('uniqueid', $booking_infos->customer_id)->first()->email;
            // return response()->json($customerEmail);
            // try {
               // Mail::to($customerEmail)->send(new BookingConfirm($data));
            // } catch (\Exception $e) {
                // DB::rollBack();
                // throw $e;
            // }

            // DB::commit();

            return response()->json($result, 200, ['message' => 'success']);
        // } catch (\Exception $e) {
        //     if ($e instanceof \Illuminate\Database\QueryException) {
        //         DB::rollBack();
        //         return response()->json([$e]);
        //     }

        //     return response()->json(['error' => 'An error occurred'], 500);
        // }
    }


    public function cancel(Request $request)
    {
        $bookingid = $request->booking;
        $bookingDetail = DB::table('booking')->where('uniqueid', $bookingid)->first();
        try {
            if ($bookingDetail->status === "active") {
                DB::table('booking')->where('uniqueid', $bookingid)->update([
                    'status' => 'cancel'
                ]);

                $booking_infos = DB::table('booking')
                    ->leftJoin('customer', 'customer.uniqueid', '=', 'booking.customer_id')
                    ->leftJoin('barber', 'barber.uniqueid', '=', 'booking.barber_id')
                    ->leftJoin('service', 'service.uniqueid', '=', 'booking.service_id')
                    ->leftJoin('booking_time', 'booking_time.uniqueid', '=', 'booking.time_period_id')
                    ->where('booking.uniqueid', $bookingid)
                    ->select('booking.*', 'customer.name as customer_name', 'barber.barber_name', 'service.service_name', 'service.duration', 'booking_time.time_period')
                    ->first();

                $services = DB::table('booking')
                    ->leftJoin('service', 'service.uniqueid', '=', 'booking.service_id')
                    ->where('booking.uniqueid', $bookingid)
                    ->select('service.*')
                    ->get();

                $total_amount = DB::table('booking')
                    ->leftJoin('service', 'service.uniqueid', '=', 'booking.service_id')
                    ->where('booking.uniqueid', $bookingid)
                    ->sum('service.pricing');

                $data = [
                    'subject' => 'Sorry! Your appointment is cancelled at NOT Barber Shop',
                    'body' => 'Your appointment has been cancelled.',
                    'booking_infos' => $booking_infos,
                    'services' => $services,
                    'total_amount' => $total_amount,
                    // 'website' => 'u-booking.com'
                ];

                $customerEmail = DB::table('customer')->where('uniqueid', $booking_infos->customer_id)->first()->email;

                try {
                    Mail::to($customerEmail)->send(new AppointmentCancel($data));
                    // dd("Email Send Successfully.");
                } catch ( \Exception $e) {
                    DB::rollBack();
                    throw $e;
                }

                DB::commit();

                return response()->json(['status' => 200, 'message' => 'Success']);
            } else {
                DB::rollBack();
                return response()->json(['status' => 400, 'message' => 'Fail']);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['status' => 400, 'message' => 'Encounter error']);
        }




    }

    public function destroy($id)
    {
        $booking = Booking::where('uniqueid', $id)->first();
        $booking->delete();

        return response()->json(['status_code' => 200, 'message' => 'success']);
    }

    public function update(Request $request, $id)
    {
        try {
            $booking = Booking::where('uniqueid', $id)->first();
            $booking->customer_id = $request->customer_id;
            $booking->barber_id = $request->barber_id;
            $booking->service_id = $request->service_id;
            $booking->date = $request->date;
            $booking->time_period_id = $request->time_period_id;
            $booking->status = 'active';
            $booking->save();

            return response()->json($booking, 200, ['message' => 'success']);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                return response()->json([$e]);
            }

            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
    public function checkBookings(Request $request)
    {
        $selectedServices = $request->input('services', []);
        $date = $request->input('date');
        $user = $request->input('user');
        $formattedDate = date('Y-m-d', strtotime($date));
        $bookedServices = Booking::whereDate('date', $formattedDate)
            ->where('customer_id', $user)
            ->whereIn('service_id', $selectedServices)
            ->with('service')
            ->get();
        if ($bookedServices->count() > 0) {
            return response()->json(['alreadyBooked' => true]);
        }

        return response()->json(['alreadyBooked' => false]);
    }
    public function checkStaffBookings(Request $request)
    {
        $staffId = $request->input('staff');
        $date = $request->input('date');
        $formattedDate = date('Y-m-d', strtotime($date));
        $bookedStaff = Booking::where('barber_id', $staffId)
            ->whereDate('date', $formattedDate)
            ->first();

        return response()->json(['bookedStaff' => !!$bookedStaff]);
    }

    public function checkTimeBooking(Request $request)
    {
        $date = $request->input('date');
        $hour = $request->input('time');
        $barber = $request->input('barber');

        if($barber != "anybarber"){
            $formattedDate = date('Y-m-d', strtotime($date));

        $isBooked = Booking::where('date', $formattedDate)
            ->where('time_period_id', $hour)
            ->where('barber_id', $barber)
            ->exists();

        $bookingTime = Time::where('uniqueid', $hour)->value('time_period');
        $currentTime = Carbon::now()->format('H:i');
        $bookingDateTime = Carbon::createFromFormat('H:i', $bookingTime);

        if ($isBooked) {
            return response()->json(['isBooked' => true, 'message' => 'This time is already booked by the selected barber. Please choose another time.']);
        }
        $minimumTimeDifference = 30;
        $todayDate=Carbon::now()->format('Y-m-d');
        if($formattedDate == $todayDate) {
            if ($bookingDateTime->lt(Carbon::now())) {
                return response()->json(['isBooked' => true, 'message' => 'Your booking time is in the past.']);
            }
            if (Carbon::now()->diffInMinutes($bookingDateTime) < $minimumTimeDifference) {
                return response()->json(['isBooked' => true, 'message' => 'Minimum 30 minutes required between booking time and current time.']);
            }
        }
        return response()->json(['todayDate' => Carbon::now()]);
        }else{
            $hour_time = Carbon::parse($hour)->format('H:i');


        $currentDate = Carbon::parse($date)->format('Y-m-d');

        $day = date('l',strtotime($request->date));

        $minimumTimeDifference = 30;
        $todayDate=Carbon::now()->format('Y-m-d');

        $time_uniqueId = DB::table('booking_time')->where('time_period',$hour)->first();

        if($currentDate == $todayDate) {
            // if ($hour_time < Carbon::now()->format('H:i')) {
            //     logger("Your booking time is in the past.");
            //     return response()->json(['isBooked' => true, 'message' => 'Your booking time is in the past.']);
            // }
            if (Carbon::now()->diffInMinutes($hour) < $minimumTimeDifference) {
                return response()->json(['isBooked' => true, 'message' => 'Minimum 30 minutes required between booking time and current time.']);
            }
        }

        $barbers = [];
        $barbers_available = DB::table('barber')->get();

        foreach ($barbers_available as $barber_available) {
            $work_days = explode(",", $barber_available->working_days);
            if (in_array(date('N', strtotime($request->date)), $work_days)) {
                array_push($barbers,$barber_available);
            }
        }

        if(!isset($barbers)){
            return response()->json(['isBooked' => true, 'message' => 'No Barber Available.']);
        }

        return response()->json(['barber' => $barbers[0],'time_unique'=> $time_uniqueId]);

        $result = null;

        foreach($barbers as $bar){
		if($result != null){
			continue;
		}
            $absence = BarberAbsence::where('barber_id',$bar->uniqueid)
                                    ->whereDate('day',$currentDate)
                                    ->where('time_period_id', null)
                                    ->get();

            if(count($absence) != 0){
                continue;
            }

            $unavailable_time = [];

            $available_times = [];

            $available_time_array = [];

            $available_time_arr = [];

            $bookings = Booking::where('booking.barber_id', $bar->uniqueid)
                                    ->whereDate('booking.date', $currentDate)
                                    ->where('booking.status', 'active')
                                    ->get();


            $absence_time = DB::table('barber_absence')->where('barber_id', $bar->uniqueid)->where('day', $currentDate)->pluck('time_period_id')->first();

            if($absence_time){
                $sub_absence_time = Carbon::parse($absence_time)->subMinutes(30)->format('H:i');
                if($hour_time >= $sub_absence_time){
                    continue;
                }
            }

            if(count($bookings) != 0){

                foreach($bookings as $booking){

                    $service_durations = Booking::join('service', 'booking.service_id', '=', 'service.uniqueid')
                                        ->where('booking.uniqueid', $booking->uniqueid)
                                        ->pluck('service.duration')
                                        ->toArray();

                    $total_duration = array_sum($service_durations);

                    $previous_time_periods = Time::where('id', '<', function ($query) use ($booking) {
                                                $query->select('id')
                                                    ->from('booking_time')
                                                    ->where('uniqueid', $booking->time_period_id)
                                                    ->limit(1);
                                            })->orderByDesc('id')
                                            ->take(3)
                                            ->pluck('time_period')
                                            ->toArray();

                    $start_time = Time::where('uniqueid', $booking->time_period_id)
                                        ->value('time_period');

                    $carbonStartTime = Carbon::parse($start_time);

                    $end_time = $carbonStartTime->addMinutes($total_duration)->format('H:i');

                    $combined_time_periods = array_merge($previous_time_periods, [$start_time]);

                    array_push($unavailable_time, ['time_periods' => $combined_time_periods, 'end_time' => $end_time]);

                    $booking_time = DB::table('booking_time');

                    foreach($unavailable_time as $un_time){
                        $hidden_duration = 15;
                        $adjusted_start_time = Carbon::parse($un_time['time_periods'][0])->subMinutes($hidden_duration)->format('H:i');
                        $un_time['time_periods'][0] = $adjusted_start_time;
                        $booking_time->whereNotBetween('time_period', [$adjusted_start_time, $un_time['end_time']]);
                    }

                    $times = BarberTime::where('barber_id', $bar->uniqueid)->get();

                    foreach($times as $time) {
                        if (date('N', strtotime($request->date)) == $time->working_day) {
                            $from = $time->from == '00:00' ? '12:00' : $time->from;

                            $from = date('H:i', strtotime('-1 minute', strtotime($from)));

                            $to = $time->to;

                            $available_time = $booking_time->whereBetween('time_period', [$from, $to])->select('uniqueid', 'time_period')->get();

                            $available_times = $available_time;
                        }
                    }
                }

                foreach($available_times as $ava) {
                    array_push($available_time_array,$ava->time_period);
                }

                if(in_array($hour,$available_time_array) != 1)
                {
                    continue;
                }
                $result = $bar;

            }else{
                $result = $bar;
            }
        }
        if($result != null){
        return response()->json(['barber' => $bar,'time_unique'=> $time_uniqueId]);
        }else{
                return response()->json(['isBooked' => true, 'message' => 'Your booking time is in the past.']);
        }
        }
    }

    public function fillAmount(Request $request)
    {
        try {
            $bookings = Booking::get();
            foreach($bookings as $booking){
                $amount = $booking->service != null ? $booking->service->pricing : 0;
                $book = Booking::find($booking->id);
                $book->amount = $amount;
                $book->save();
            }

            return response()->json($bookings, 200, ['message' => 'success']);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                return response()->json([$e]);
            }

            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

}
