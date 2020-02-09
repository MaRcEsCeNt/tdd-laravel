<?php

namespace Tests\Unit;

use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_company_id_is_recorded()
    {
        Product::create([
            'title' => 'Product Title',
            'packagedOn' => '11/01/2019',
            'company_id' => 1,
        ]);

        $this->assertCount(1, Product::all());
    }
}
