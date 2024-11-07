<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Customers;
use App\Models\BookingTime;
use Illuminate\Http\Request;
use App\Models\BarberAbsence;
use App\Mail\AppointmentCancel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absence_details = BarberAbsence::with('barber')
        ->whereHas('barber')
        ->latest('id')
        ->get();
        $barbers=Barber::get();
        return view('absence.index',compact('absence_details','barbers'));
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
    // public function store(Request $request)
    // {
    //     // dd($request);
    //     DB::table('barber_absence')->insert([
    //         'barber_id'=>$request->barber_select,
    //         'time_period_id'=>Null,
    //         'day'=>$request->date,
    //     ]);
    //     return redirect('absence')->with("success", 'Absence Information Created Successfully');
    // }
    public function store(Request $request)
    {
        // dd($request);

        if($request->leave_time)
        {
            $time_period = BookingTime::where('time_period', '>', $request->leave_time)->pluck('uniqueid');
        }
        else
        {
            $time_period= '';
        }

        $response_text = '';
        try {
            DB::beginTransaction();
            BarberAbsence::insert([
                'barber_id'=>$request->barber_select,
                'time_period_id'=>$request->leave_time,
                'day'=>$request->date,
                'description'=>$request->description
            ]);

             $query = Booking::whereDate('booking.date', $request->date)
                        ->where('barber_id', $request->barber_select);
            if (is_array($time_period)) {
                $query->whereIn('time_period_id', $time_period);
            }
            $query->update(['booking.status' => 'cancel']);

            DB::commit();

            $response_text = 'Absence Information Created Successfully';
        } catch (Exception $e) {
            DB::rollback();
            $response_text = "Transaction failed: " . $e->getMessage();
        }


        $query = Booking::whereDate('booking.date', $request->date)
                ->where('barber_id', $request->barber_select)
                ->where('booking.status', 'cancel');

            if (is_array($time_period)) {
                $query->whereIn('time_period_id', $time_period);
            }

            $booking_cannels = $query->get();

        foreach($booking_cannels as $booking_cannel)
        {
            $booking_infos = Booking::leftJoin('customer', 'customer.uniqueid', '=', 'booking.customer_id')
                    ->leftJoin('barber', 'barber.uniqueid', '=', 'booking.barber_id')
                    ->leftJoin('service', 'service.uniqueid', '=', 'booking.service_id')
                    ->leftJoin('booking_time', 'booking_time.uniqueid', '=', 'booking.time_period_id')
                    ->where('booking.uniqueid', $booking_cannel->uniqueid)
                    ->select('booking.*', 'customer.name as customer_name', 'barber.barber_name', 'service.service_name', 'service.duration', 'booking_time.time_period')
                    ->first();

            $services = Booking::leftJoin('service', 'service.uniqueid', '=', 'booking.service_id')
                    ->where('booking.uniqueid', $booking_cannel->uniqueid)
                    ->select('service.*')
                    ->get();

            $total_amount = Booking::leftJoin('service', 'service.uniqueid', '=', 'booking.service_id')
                    ->where('booking.uniqueid', $booking_cannel->uniqueid)
                    ->sum('service.pricing');

            $data = [
                    'subject' => 'Sorry! Your appointment is cancelled at NOT Barber Shop',
                    'body' => 'Your appointment has been cancelled.',
                    'booking_infos' => $booking_infos,
                    'services' => $services,
                    'total_amount' => $total_amount,
                ];

            $customerEmail = Customers::where('uniqueid', $booking_infos->customer_id)->first()->email;
            Mail::to($customerEmail)->send(new AppointmentCancel($data));

        }

        return redirect('absence')->with("success", $response_text);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // dd($id);
        BarberAbsence::where('barber_id',$id)->delete();
        return redirect('absence')->with("success", 'Absence Information Deleted Successfully');
    }
}
