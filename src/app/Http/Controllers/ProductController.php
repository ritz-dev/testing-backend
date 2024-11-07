<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::get();
        $products_to_sold = Product::where('stocks_left', '>', 0)->get();

        return view('products.index', compact('products','products_to_sold'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|string',
            'quantity' => 'required|numeric',
            'description' => 'required',
            'photo' => 'mimes:jpeg,png,jpg,gif,svg'
        ]);

        if ($validator) {
            if($request->file('photo')) {
                $photoName = time().'.'.$request->photo->getClientOriginalName();
                $request->photo->storeAs('public/product_photos', $photoName);
            }

            $product = new Product();
            $product->uniqueid = UniqueIDController::generateUniqueID('products');
            $product->product_name = $request->product_name;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->stocks_left= $request->quantity;
            $product->description = $request->description;
            $product->photo = $photoName;
            $product->save();

            return redirect('products/')->with("success", 'New Product created sucessfully');
        }else{
            return redirect()->back()->withErrors($validator);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::where('uniqueid',$id)->first();
        return view('products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|string',
            // 'quantity' => 'required|numeric',
            'description' => 'required',
            'photo' => 'mimes:jpeg,png,jpg,gif,svg'
        ]);

        if ($validator) {
            $product = Product::where('uniqueid',$id)->first();

            if($request->file('photo')) {
                $photoName = time().'.'.$request->photo->getClientOriginalName();
                $request->photo->storeAs('public/product_photos', $photoName);
                Storage::delete('public/product_photos/'. $product->photo);
                $product->photo = $photoName;
            }

            $product->product_name = $request->product_name;
            $product->price = $request->price;
            $product->quantity += $request->fill_qty;
            $product->stocks_left += $request->fill_qty;
            $product->description = $request->description;
            $product->save();

            return redirect('products/')->with("success", 'Product is Successfully Updated');
        }else{
            return redirect()->back()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::where('uniqueid',$id)->first();
        if($product->photo){
            Storage::delete('public/product_photos/'.$product->photo);
        }
        $product->photo = 0;
        $product->save();

        $product->delete();

        return redirect('/products')->with('success','Product successfully deleted');
    }

    public function productDetails(Request $request)
    {
        $product=$request->product_uniqueid;
        $details=Product::where('uniqueid',$product)->first();

        return response()->json($details);
    }

    public function soldForm(Request $request)
    {

        $stocks_left=DB::table('products')->where('uniqueid',$request->product_select)->first()->stocks_left;

        if($stocks_left >= $request->qty){
            $updated_stocks=$stocks_left - $request->qty;

            DB::table('products')->where('uniqueid',$request->product_select)->update([
                'stocks_left'=>$updated_stocks,
            ]);

            DB::table('products_sold')->insert([
                'product_id'=>$request->product_select,
                'qty'=>$request->qty,
                'date'=>$request->date,
                'total_amount'=>preg_replace("/[^0-9]/", "", $request->total_amount),
            ]);
            return redirect('products/')->with("success", 'Sold product was recorded successfully');

        }else{
            return redirect()->back()->with("error","Failed! Not enough stocks to sold! Please check again!");
        }

    }
}
