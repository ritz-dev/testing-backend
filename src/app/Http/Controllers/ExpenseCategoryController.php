<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ExpenseCategory::all();

        return view('expense_category.index', compact('categories'));
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
        $validate = $request->validate([
            'title' => 'required'
        ]);

        $category = new ExpenseCategory();
        $category->uniqueid = UniqueIDController::generateUniqueID('expense_category');
        $category->title = $request->title;
        $category->save();

        return redirect()->back()->with('success', 'Successfully created a category');
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
        $category = DB::table('expense_category')->where('uniqueid', $id)->first();

        return view('expense_category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'title' => ['required', Rule::unique('expense_category')->ignore($id)]
        ]);

        ExpenseCategory::where('uniqueid', $id)->update([
            'title' => $request->title
        ]);

        return redirect()->route('expense_category.index')->with('success', 'Successfully updated a category');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ExpenseCategory::where('uniqueid', $id)->delete();

        return redirect()->back()->with('success', 'Successfully deleted a category');
    }
}
