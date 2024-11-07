<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services=Service::get();
        return view('services.index',compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'service_name' => 'required',
            'duration' => 'required|numeric|max:255',
            'pricing' => 'required|numeric|min:0',
            'description' => 'required|max:255'
        ]);

        if($validator) {
            $service = new Service();
            $service->uniqueid = UniqueIDController::generateUniqueID('service');
            $service->service_name = $request->service_name;
            $service->duration = $request->duration;
            $service->pricing = $request->pricing;
            $service->description = $request->description;
            $service->is_package = $request->has('is_package');
            $service->save();
            return redirect('/services')->with('success','Service successfully added');
        } else {
            return redirect()->back()->with('error',$validator);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $service = Service::where('uniqueid',$id)->first();
        return view('services.edit',compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'service_name' => 'required',
            'duration' => 'required|numeric|max:255',
            'pricing' => 'required|numeric|min:0',
            'description' => 'required|max:255'
        ]);

        if($validator) {
            $service = Service::where('uniqueid',$id)->first();
            $service->service_name = $request->service_name;
            $service->duration = $request->duration;
            $service->pricing = $request->pricing;
            $service->description = $request->description;
            $service->is_package = $request->has('is_package');
            $service->save();
            return redirect('/services')->with('success','Service successfully updated');
        } else {
            return redirect()->back()->with('error',$validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::where('uniqueid',$id)->first();
        $service->delete();
        return redirect('/services')->with('success','Service successfully deleted');
    }
}
