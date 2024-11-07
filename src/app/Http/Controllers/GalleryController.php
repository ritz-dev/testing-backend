<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::get();
        return view('gallery.index',compact('galleries'));
    }
    public function create()
    {
        return view('gallery.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg'
        ]);
        if($validator){
            $gallery = new Gallery();
            $gallery->name = $request->name;
            if ($request->file('image')) {
                $photoName = time() . '.' . $request->image->getClientOriginalName();
                $request->image->storeAs('public/gallery', $photoName);
            }
            $gallery->image = $photoName;
            $gallery->save();
            return redirect('gallery')->with("success", 'Gallery created successfully');
        }else {
            return redirect()->back()->withErrors($validator);
        }
    }
    public function edit(string $id)
    {
        $galleries = Gallery::where('id', $id)->first();
        return view('gallery.edit', compact('galleries'));
    }
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'name' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg'
        ]);
        if ($validator) {
            $gallery = Gallery::where('id', $id)->first();

            if ($request->file('image') && $gallery->image) {

                Storage::delete('public/gallery', $gallery->image);

                $photoName = time() . '.' . $request->image->getClientOriginalName();
                $request->image->storeAs('public/gallery', $photoName);
                $gallery->image = $photoName;
            }
            $gallery->name = $request->name;
            $gallery->image = $photoName;
            $gallery->update();
            return redirect('gallery')->with("success", 'Gallery Updated Successfully');
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function destroy(string $id)
    {
        $gallery = Gallery::findOrFail($id);

        $gallery->image = 0;
        $gallery->save();

        Storage::delete('public/gallery', $gallery->image);

        $gallery->delete();
        return redirect('gallery')->with("success", 'Image Deleted Successfully');
    }
}
