<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::query();
        if (request()->has('name') || request()->has('description') || request()->has('category')) {
            $products->where('name', 'like', '%' . request('name') . '%')
                ->orWhere('description', 'like', '%' . request('description') . '%');
            if (!empty(json_decode(request('category')))) {
                $products->whereIn('category', json_decode(request('category')));
            }
        }
        return $products->orderBy('id', 'DESC')->paginate(6);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $images = [];
            if (is_array($request->file('images'))) {
                foreach ($request->file('images') as $file) {
                    $name = time() . rand(1, 50) . '.' . $file->extension();
                    $file->move(public_path('images/products'), $name);
                    array_push($images, $name);
                }
            }

            $product = Product::create([
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'images' => !empty($images) ? json_encode($images) : null,
                'date_time' => $request->date_time
            ]);
        } catch (\Throwable $error) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => "Failed to add product",
                    'type' => 'error',
                    'error' => $error
                ],
                500
            );
        }
        DB::commit();
        return response()->json(
            [
                'message' => "{$product->name} has been successfully created",
                'type' => 'success',
            ],
            200
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id)->update($request->all());
        } catch (\Throwable $error) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => "Failed to update product",
                    'type' => 'error',
                    'error' => $error
                ],
                500
            );
        }
        DB::commit();
        return response()->json(
            [
                'message' => "{$product->name} has been successfully updated",
                'type' => 'success',
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            $product->delete();
        } catch (\Throwable $error) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => "Failed to delete product",
                    'type' => 'error',
                    'error' => $error
                ],
                500
            );
        }
        DB::commit();
        return response()->json(
            [
                'message' => "{$product->name} has been successfully deleted",
                'type' => 'success',
            ],
            200
        );
    }
}
