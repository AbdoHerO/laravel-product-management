<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Product as ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Presenters\ProductPresenter;
use Illuminate\Support\Facades\Log;

class ProductController extends BaseController
{

    public function index()
    {
        $products = Product::all()->present(ProductPresenter::class);
        return $this->sendResponse($products, 'Product retrieved successfully.');
        // return $this->sendResponse(ProductResource::collection($products), 'Product retrieved successfully.');
    }


    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'unit_price' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $image = $this->uploadImage($request);

        $input['image'] = $image;

        $product = Product::create($input);

        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    public function show($id)
    {
        $product = Product::where('id', $id)->first();

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }


    public function update(Request $request, $id)
    {
        // Access the data from the FormData object
        $input = $request->all(); // This will retrieve form input values
        $product = Product::findOrFail($id);

        // Validate the input
        $validator = Validator::make($input, [
            'name' => 'required',
            'unit_price' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $image = $this->uploadImage($request, $product);
        $input['image'] = $image;

        $product->name = $input['name'];
        $product->image = $input['image'];
        $product->description = $input['description'];
        $product->unit_price = $input['unit_price'];
        $product->size = $input['size'];
        $input['type'] != null ?? $product->type = $input['type'] ;
        $product->save();

        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse([], 'Product deleted successfully.');
    }

    public function uploadImage(Request $request, $product = null)
    {
        if ($request->hasFile('image')) {

            // $filename = $request->name;
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename =  rand() . '_' . time() . '.' . $extension;

            // $path = $request->file('image')->move(public_path('storage/products'), $autolink);
            $path = $request->file('image')->storeAs('public/products', $filename);

            if ($path) {
                // Delete the old picture from storage if it exists
                if ($product && $product->image) {
                    Storage::delete('public/products/' . $product->image);
                }

                return $filename;
            }
        } else {
            return null;
        }
    }

    public function searchForProduct(Request $request)
    {

        // $products = Product::orderBy('id', 'desc');
        $products = DB::table('products');



        if (!empty($request->search)) {
            $products = $products
                ->where('name', 'LIKE', '%' . Str::lower($request->search) . '%')
                ->orWhere('unit_price', '=', $request->search)
                ->orWhere('description', '=', $request->search)
                ->orWhere('size', '=', $request->search)
                ->orWhere('type', '=', $request->search);
        }

        $products = $products->select('id')->get();
        $products_ids = [];
        foreach ($products as $product)
            array_push($products_ids, $product->id);

        $products = Product::whereIn('id', $products_ids)->get()->present(ProductPresenter::class);
        // $products = $products->get()->present(ProductPresenter::class);

        return $this->sendResponse($products, 'Product retrieved successfully.');
    }
}
