<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function store() {
        Product::create($this->validateRequest());
    }

    public function update(Product $product) {
        $product->update($this->validateRequest());
    }

    public function validateRequest() {
        return request()->validate([
            'title' => 'required',
            'brand' => 'required'
        ]);
    }
}
