<?php

namespace App\Http\Controllers;

use App\Models\HomeView;
use Illuminate\Http\Request;
use App\Http\Requests\HomeViewRequest;
use Illuminate\Support\Facades\Storage;

class HomeViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $home_views = HomeView::latest('id')->get();
        return view('home_views.index',compact('home_views'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('home_views.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HomeViewRequest $request)
    {
        $home_view = new HomeView;
        if($request->file('photo')){
            $photoName = $request->photo->getClientOriginalName();
            $photo = time().$photoName;
            $request->photo->storeAs('public/homeviews', $photo);
            $home_view->photo = $photo;
        }
        $home_view->description = $request->description;
        $home_view->save();

        return redirect()->route('home_views.index')->with('success','Created Successfully!');
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
	$home_view = HomeView::findOrFail($id);
        return view('home_views.edit',compact('home_view'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $home_view = HomeView::findOrFail($id);

        if($request->photo && $home_view->photo){

            Storage::delete('public/homeviews', $home_view->photo);

            $photoName = $request->photo->getClientOriginalName();
            $photo = time().$photoName;
            $request->photo->storeAs('public/homeviews', $photo);
            $home_view->photo = $photo;
        }
        $home_view->description = $request->description;
        $home_view->save();

        return redirect()->route('home_views.index')->with('success','Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
