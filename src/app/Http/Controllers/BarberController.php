<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Barber;
use App\Models\Customer;
use App\Models\BarberTime;
use App\Models\SalaryDate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BarberAbsence;
use App\Models\BarberCommission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class BarberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barbers = Barber::all();
        // dd($barbers);
        return view('barbers.index', compact('barbers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $working_days=[1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday',7=>'Sunday'];
        return view('barbers.create',compact('working_days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'barber_name' => 'required',
            'contact_number' => 'required',
            'password' => 'required',
            'barber_photo' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $barber_unid=UniqueIDController::generateUniqueID('barber');
        $barber = new Barber;
        if ($validator) {
            $working_days=$request->work_days;
            if($working_days == null){
                $working_days='1,2,3,4,5,6,7';
            }else{
                $working_days=implode(',',$working_days);
            }

            if ($request->file('barber_photo')) {
                $photoName = time() . '.' . $request->barber_photo->getClientOriginalName();
                $request->barber_photo->storeAs('public/barber_photos', $photoName);
            }
            $barber_unid=UniqueIDController::generateUniqueID('barber');
            $barber = new Barber;
            $barber->uniqueid = $barber_unid;
            $barber->barber_name = $request->barber_name;
            if ($request->filled('email')) {
                $barber->email = $request->email;
            }else{
                $barber->email=null;
            }
            $barber->contact_number = $request->contact_number;
            $barber->password = Hash::make($request->password);
            $barber->shop_id = $request->shop_id;
            $barber->join_date = $request->join_date;
            $barber->working_days=$working_days;
            $barber->commission_rate = 10;
            $barber->barber_photo = $photoName;
            $barber->save();

            $data = [1, 2, 3, 4, 5, 6, 7];
            $daysToIterate = $request->work_days ?? $data;

            foreach ($daysToIterate as $item) {
                $barber_time = BarberTime::create([
                    'barber_id' => $barber_unid,
                    'working_day' => $item,
                    'from' => $request->input('from_' . $item),
                    'to' => $request->input('to_' . $item),
                ]);
            }

            $user = new Users;
            $user->uniqueid = $barber_unid;
            $user->name = $barber->barber_name;
            $user->email = $barber->email;
            $user->password = $barber->password;
            $user->phone=$barber->contact_number;
            $user->role_id = 0;
            $user->save();

            return redirect('barbers')->with("success", 'New barber created successfully');
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }


    public function edit(string $id)
    {
        $working_days=[1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday',7=>'Sunday'];
        $barber = Barber::with('times')->where('uniqueid', $id)->first();

        return view('barbers.edit', compact('barber','working_days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $validator = $request->validate([
        'barber_name' => 'required',
        'contact_number' => 'required',
        'barber_photo' => 'mimes:jpeg,png,jpg,gif,svg|max:20480'
    ]);

    if ($validator) {
        $barber = Barber::where('uniqueid', $id)->first();

        $working_days = $request->work_days;
        if ($working_days == null) {
            $working_days = '1,2,3,4,5,6,7';
        } else {
            $working_days = implode(',', $working_days);
        }

        if ($request->hasFile('barber_photo')) {
            $photoName = time() . '.' . $request->barber_photo->getClientOriginalName();
            $request->barber_photo->storeAs('public/barber_photos', $photoName);
            if ($barber->barber_photo) {
                Storage::delete('public/barber_photos/' . $barber->barber_photo);
            }
            $barber->barber_photo = $photoName;
        }

        $barber->barber_name = $request->barber_name;
        $barber->email = $request->filled('email') ? $request->email : null;
        $barber->contact_number = $request->contact_number;
        $barber->password = Hash::make($request->password);
        $barber->shop_id = $request->shop_id;
        $barber->working_days = $working_days;
        $barber->save();

        $data = [1, 2, 3, 4, 5, 6, 7];
            $daysToIterate = $request->work_days ?? $data;

            //delete barber time
            $barber->times()->delete();

            foreach ($daysToIterate as $item) {
                $barber_time = BarberTime::create([
                    'barber_id' => $barber->uniqueid,
                    'working_day' => $item,
                    'from' => $request->input('from_' . $item),
                    'to' => $request->input('to_' . $item),
                ]);
            }

        // Update corresponding user record if exists
        $user = Users::where('uniqueid', $barber->uniqueid)->first();
        if ($user) {
            $user->name = $barber->barber_name;
            $user->email = $barber->email;
            $user->password = $barber->password;
            $user->phone = $barber->contact_number;
            $user->save();
        }

        return redirect('barbers')->with("success", 'Barber updated successfully');
    } else {
        return redirect()->back()->withErrors($validator);
    }
}

    // public function update(Request $request, string $id)
    // {
    //     $validator = $request->validate([
    //         'barber_name' => 'required|string|max:255',
    //         'contact_number' => 'required|numeric',
    //         'barber_photo' => 'mimes:jpeg,png,jpg,gif,svg'
    //     ]);
    //     if ($validator) {
    //         $working_days=$request->work_days;
    //         if($working_days == null){
    //             $working_days='1,2,3,4,5,6,7';
    //         }else{
    //             $working_days=implode(',',$working_days);
    //         }
    //         $barber = DB::table('barber')->where('uniqueid', $id)->first();
    //         $photoName = $barber->barber_photo;

    //         if ($request->file('barber_photo')) {
    //             $photoName = time() . '.' . $request->barber_photo->getClientOriginalName();
    //             $request->barber_photo->storeAs('public/barber_photos', $photoName);
    //             Storage::delete('public/barber_photos/' . $barber->barber_photo);
    //             $barber->barber_photo = $photoName;
    //         }
            // $barber = Barber::findOrFail($id);
    //         $barber->barber_name = $request->barber_name;
    //         if ($request->filled('email')) {
    //             $barber->email = $request->email;
    //         }else{
    //             $barber->email=null;
    //         }
    //         $barber->contact_number = $request->contact_number;
    //         $barber->working_days=$working_days;
    //         $barber->barber_photo = $photoName;
    //         $barber->shop_id = $request->shop_id;
    //         $barber->commission_rate = $request->c_rate;
    //         $barber->update();

    //         $user = Users::where('uniqueid', $barber->uniqueid)->first();
    //         if ($user) {
    //             $user->name = $barber->barber_name;
    //             $user->email = $barber->email;
    //             $user->password = $barber->password;
    //             $user->phone= $barber->contact_number;
    //             $user->save();
    //         }

    //         return redirect('barbers')->with("success", 'Barber Information Updated Successfully');
    //     } else {
    //         return redirect()->back()->withErrors($validator);
    //     }
    // }
    // public function update(Request $request, string $id)
    // {
    //     $request->validate([
    //         'barber_name' => 'required|string|max:255',
    //         'contact_number' => 'required|numeric',
    //         'barber_photo' => 'nullable|mimes:jpeg,png,jpg,gif,svg'
    //     ]);

    //     $barber = Barber::findOrFail($id);
    //     $barber->barber_name = $request->barber_name;
    //     $barber->email = $request->filled('email') ? $request->email : null;
    //     $barber->contact_number = $request->contact_number;
    //     $barber->working_days = $request->filled('work_days') ? implode(',', $request->work_days) : '1,2,3,4,5,6,7';
    //     $barber->shop_id = $request->shop_id;
    //     $barber->commission_rate = $request->c_rate;

    //     if ($request->hasFile('barber_photo')) {
    //         $photoName = time() . '.' . $request->barber_photo->getClientOriginalExtension();
    //         $request->barber_photo->storeAs('public/barber_photos', $photoName);
    //         Storage::delete('public/barber_photos/' . $barber->barber_photo);
    //         $barber->barber_photo = $photoName;
    //     }

    //     $barber->save();
    //     dd($barber);

    //     // Update user information if associated
    //     $user = Users::where('uniqueid', $barber->uniqueid)->first();
    //     if ($user) {
    //         $user->name = $barber->barber_name;
    //         $user->email = $barber->email;
    //         $user->password = $barber->password;
    //         $user->phone = $barber->contact_number;
    //         $user->save();
    //     }

    //     return redirect('barbers')->with("success", 'Barber Information Updated Successfully');
    // }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barber = Barber::findOrFail($id);
        $barber->delete();
	    Users::where('uniqueid',$barber->uniqueid)->delete();
        $barber->times()->delete();
        SalaryDate::where('barber_uniqueid',$id)->delete();

        return redirect('barbers')->with("success", 'Barber Deleted Successfully');
    }
}
