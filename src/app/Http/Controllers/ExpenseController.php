<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UniqueIDController;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::leftJoin('expense_category', 'expense_category.uniqueid', '=', 'expense.category_id')
                        ->select('expense.*', 'expense_category.title as category')
                        ->orderBy('expense.created_at', 'desc')
                        ->get();

        $categories = ExpenseCategory::get();

        return view('expense.index', compact('expenses', 'categories'));
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
        $validatedData = $request->validate([
            'category_id' => 'required',
            'date' => 'required|date',
            'description' => 'required',
            'amount' => 'required|numeric',
        ]);

        $expense = new Expense();
        $expense->uniqueid = UniqueIDController::generateUniqueID('expense');
        $expense->category_id = $request->category_id;
        $expense->date = $request->date;
        $expense->description = $request->description;
        $expense->amount = $request->amount;
        $expense->save();

        return redirect()->back()->with('success', 'Expense added successfully.');
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
        $expense = Expense::where('uniqueid', $id)
                    ->first();

        $categories = ExpenseCategory::all();

        return view('expense.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'category_id' => 'required',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'required',
        ]);

        Expense::where('uniqueid', $id)->update([
            'category_id' => $request->category_id,
            'date' => $request->date,
            'amount' => $request->amount,
            'description' => $request->description
        ]);

        return redirect()->route('expense.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Expense::where('uniqueid', $id)->delete();

        return redirect()->route('expense.index')->with('success', 'Expense deleted successfully');
    }
}
