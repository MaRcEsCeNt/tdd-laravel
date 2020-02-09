<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function storeProduct()
    {
        $response = $this->post('/products', [
            'title' => 'Product Title',
            'brand' => 'Brand Name',
            'packagedOn' => '01/11/2019'
        ]);

        $this->assertCount(1, Product::all());

        $product = Product::first();
        $response->assertRedirect($product->path());
    }

    /** @test */
    public function updateProduct()
    {
        $this->post('/products', [
            'title' => 'Product Title',
            'brand' => 'Brand Name',
            'packagedOn' => '01/11/2019'
        ]);

        $product = Product::first();

        $response = $this->patch($product->path(), [
            'title' => 'New Product Title',
            'brand' => 'New Brand Name',
            'packagedOn' => '01/12/2019'
        ]);

        $this->assertEquals('New Product Title', Product::first()->title);
        $this->assertEquals('New Brand Name', Product::first()->brand);
        $this->assertEquals('01/12/2019', Product::first()->packagedOn);
        $response->assertRedirect($product->fresh()->path());
    }

    /** @test */
    public function destroyProduct()
    {
        $this->post('/products', [
            'title' => 'Product Title',
            'brand' => 'Brand Name',
            'packagedOn' => '01/11/2019'
        ]);

        $product = Product::first();
        $this->assertCount(1, Product::all());

        $response =$this->delete($product->path());
        $this->assertCount(0, Product::all());
        $response->assertRedirect('/products');
    }

    /** @test */
    public function titleIsRequired()
    {
        $response = $this->post('/products', [
            'title' => '',
            'brand' => 'Brand Name',
            'packagedOn' => '01/11/2019'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function brandIsRequired()
    {
        $response = $this->post('/products', [
            'title' => 'Product Title',
            'brand' => '',
            'packagedOn' => '01/11/2019'
        ]);

        $response->assertSessionHasErrors('brand');
    }

        /** @test */
        public function packagedOnIsRequired()
        {
            $response = $this->post('/products', [
                'title' => 'Product Title',
                'brand' => 'Brand Name',
                'packagedOn' => ''
            ]);
    
            $response->assertSessionHasErrors('packagedOn');
        }
}
