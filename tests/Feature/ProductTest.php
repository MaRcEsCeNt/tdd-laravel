<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function storeProduct()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/products', [
            'title' => 'Product Title',
            'brand' => 'Brand Name',
        ]);

        $response->assertOk();
        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function updateProduct()
    {
        $this->withoutExceptionHandling();

        $this->post('/products', [
            'title' => 'Product Title',
            'brand' => 'Brand Name',
        ]);

        $book = Product::first();

        $response = $this->patch('/products/' . $book->id, [
            'title' => 'New Product Title',
            'brand' => 'New Brand Name',
        ]);

        $this->assertEquals('New Product Title', Product::first()->title);
        $this->assertEquals('New Brand Name', Product::first()->brand);
    }

    /** @test */
    public function titleIsRequired()
    {
        $response = $this->post('/products', [
            'title' => '',
            'brand' => 'Brand Name',
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function brandIsRequired()
    {
        $response = $this->post('/products', [
            'title' => 'Product Title',
            'brand' => '',
        ]);

        $response->assertSessionHasErrors('brand');
    }
}
