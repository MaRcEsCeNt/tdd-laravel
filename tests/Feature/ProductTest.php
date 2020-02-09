<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Product;
use Carbon\Carbon;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function storeProduct()
    {
        $response = $this->post('/products', [
            'title' => 'Product Title',
            'brand' => 'Brand Name',
            'packagedOn' => '11/01/2019'
        ]);

        $this->assertCount(1, Product::all());

        $product = Product::first();
        $response->assertRedirect($product->path());

        $this->assertInstanceOf(Carbon::class, $product->packagedOn);
        $this->assertEquals('2019/11/01', $product->packagedOn->format('Y/m/d'));

    }

    /** @test */
    public function updateProduct()
    {
        $this->post('/products', [
            'title' => 'Product Title',
            'brand' => 'Brand Name',
            'packagedOn' => '12/01/2019'
        ]);

        $product = Product::first();

        $response = $this->patch($product->path(), [
            'title' => 'New Product Title',
            'brand' => 'New Brand Name',
            'packagedOn' => '12/01/2019'
        ]);

        $this->assertEquals('New Product Title', Product::first()->title);
        $this->assertEquals('New Brand Name', Product::first()->brand);
        $this->assertInstanceOf(Carbon::class, $product->fresh()->packagedOn);
        $this->assertEquals('2019/12/01', $product->fresh()->packagedOn->format('Y/m/d'));
        $response->assertRedirect($product->fresh()->path());
    }

    /** @test */
    public function destroyProduct()
    {
        $this->post('/products', [
            'title' => 'Product Title',
            'brand' => 'Brand Name',
            'packagedOn' => '12/01/2019'
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

        /** @test */
        public function packagedOnIsADate()
        {
            $response = $this->post('/products', [
                'title' => 'Product Title',
                'brand' => 'Brand Name',
                'packagedOn' => 'String'
            ]);
    
            $response->assertSessionHasErrors('packagedOn');
            $this->assertCount(0, Product::all());

            $response = $this->post('/products', [
                'title' => 'Product Title',
                'brand' => 'Brand Name',
                'packagedOn' => '11/01/2019'
            ]);

            $response->assertSessionHasNoErrors('packagedOn');
            $this->assertCount(1, Product::all());
            $this->assertInstanceOf(Carbon::class, Product::first()->packagedOn);
            $this->assertEquals('2019/11/01', Product::first()->packagedOn->format('Y/m/d'));
        }
}
