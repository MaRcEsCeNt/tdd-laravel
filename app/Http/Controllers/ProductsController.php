<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function store() {
        $product = Product::create($this->validateRequest());
        
        return redirect($product->path());
    }

    public function update(Product $product) {
        $product->update($this->validateRequest());

        return redirect($product->path());
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect('/products');
    }

    protected function validateRequest() {
        return request()->validate([
            'title' => 'required',
            'brand' => 'required',
            'packagedOn' => 'required'
        ]);
    }
}
