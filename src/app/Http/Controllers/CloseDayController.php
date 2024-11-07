<?php

namespace App\Http\Controllers;

use App\Models\CloseDay;
use Illuminate\Http\Request;

class CloseDayController extends Controller
{
    public function index()
    {
        $closeDays = CloseDay::all();
        // return response()->json($closeDays);
        return view('close_days.index', compact('closeDays'));
    }

    public function create()
    {
        return view('close_days.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $closeDay = CloseDay::create($request->all());

        return redirect()->route('close-days.index')->with('success', 'Close day created successfully');
    }

    public function show($id)
    {
        $closeDay = CloseDay::findOrFail($id);
        return view('close_days.show', compact('closeDay'));
    }

    public function edit($id)
    {
        $closeDay = CloseDay::findOrFail($id);
        return view('close_days.edit', compact('closeDay'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $closeDay = CloseDay::findOrFail($id);
        $closeDay->update($request->all());

        return redirect()->route('close-days.index')->with('success', 'Close day updated successfully');
    }

    public function destroy($id)
    {
        $closeDay = CloseDay::findOrFail($id);
        $closeDay->delete();

        return redirect()->route('close-days.index')->with('success', 'Close day deleted successfully');
    }
}
