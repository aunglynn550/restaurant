<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductSize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ProductSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $product_id):View
    {
        $product = Product::findOrFail($product_id);
        $sizes = ProductSize::where('product_id',$product_id)->get();
        $options = ProductOption::where('product_id',$product_id)->get();

        return view('admin.product.product-size.index',compact('product','sizes','options'));
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request):RedirectResponse
    {
        $request->validate([
            'name' => ['required','max:255'],
            'price' => ['required','numeric'],
            'product_id' => ['required','integer']
        ]);

        $size = new ProductSize();

        $size->product_id = $request->product_id;
        $size->name = $request->name;
        $size->price = $request->price;

        $size->save();

        toastr('Created Successfully !','success');
        return redirect()->back();
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id):Response
    {
        try{
            $size = ProductSize::findOrFail($id);            
            $size->delete();            
            return response(['status'=> 'success', 'message'=> 'Deleted Successfully !']);
        }catch( \Exception $e){
            return response(['status'=> 'error', 'message'=> 'Something Went Wrong!']);
        }
    }
}
