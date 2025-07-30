<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id',auth()->user()->id)->with(['category', 'subCategory'])->latest('created_at')->get();
        // dd($products[0]);

        return view('e-com.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'quantity' => 'required|integer',
            'unit_cost_price' => 'required|numeric|min:0',
            'price' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value < $request->unit_cost_price) {
                        $fail('Price must be greater than or equal to Unit Cost Price.');
                    }
                },
            ],
            'is_active' => 'required|boolean',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string|max:4000'
        ]);
        $validated['user_id']=auth()->user()->id;


        if ($request->hasFile('image_url')) {
            $validated['image_url'] = $request->file('image_url')->store('products', 'public');
        }

        // Generate unique SKU
        do {
            $sku = mt_rand(10000000, 99999999);
        } while (Product::where('sku', $sku)->exists());

        $validated['sku'] = $sku;
        $product = Product::create($validated);

        return $this->getLatestRecords('Product created successfully');
    }


    public function edit($id){
        $product = Product::find($id);
        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }
    public function update(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'quantity' => 'required|integer',
            'unit_cost_price' => 'required|numeric',
            'price' => 'required|numeric',
            'is_active' => 'required|boolean',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:2000'

        ]);
        $quantity=$request->add_or_subtract_quantity;
        if($request->add_or_subtract == 1){
            $request->quantity += $quantity;
        }else{
            if($request->quantity < $quantity){
                return response()->json([
                    'success' => false,
                    'message' => 'Product quantity is not enough',
                ]);
        }
        else{
            $request->quantity -= $quantity;
        }
    }
        $product = Product::find($request->id);
         // Handle file upload if new image provided
    if ($request->hasFile('image_url')) {
        // Delete old image if exists
        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }

        $path = $request->file('image_url')->store('products', 'public');
        $product->image_url = $path;
    }

    // Update description
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->unit_cost_price = $request->unit_cost_price;
        $product->price = $request->price;
        $product->is_active = $request->is_active;
        $product->sub_category_id = $request->sub_category_id;
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->quantity = $request->quantity;
        $product->unit_cost_price = $request->unit_cost_price;
        $product->price = $request->price;
        $product->save();
        return $this->getLatestRecords('Product updated successfully');
    }
    public function destroy(Request $request){
        $product = Product::find($request->id);
        $product->delete();
        return $this->getLatestRecords('Product deleted successfully');
    }


    private function getLatestRecords($message = 'Products fetched successfully')
    {
        $products = Product::where('user_id',auth()->user()->id)->with(['category', 'subCategory'])->latest('created_at')->get();

        return response()->json([
            'success' => true,
            'html' => view('e-com.products.data-table', compact('products'))->render(),
            'message' => $message,
        ]);
    }
}
