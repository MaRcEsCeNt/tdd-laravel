<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Product;
use App\Company;
use Carbon\Carbon;

class ProductTest extends TestCase
{
      /////////////
     /// TESTS ///
    /////////////

    use RefreshDatabase;

    /** @test */
    public function storeProduct()
    {
        $response = $this->getResponseCreateStandardProduct();

        $this->assertCount(1, Product::all());

        $product = Product::first();
        $response->assertRedirect($product->path());

        $this->assertInstanceOf(Carbon::class, $product->packagedOn);
        $this->assertEquals('2019/11/01', $product->packagedOn->format('Y/m/d'));

    }

    /** @test */
    public function updateProduct()
    {
        $this->createStandardProduct();

        $product = Product::first();

        $response = $this->patch($product->path(), [
            'title' => 'New Product Title',
            'company_id' => 'New Company',
            'packagedOn' => '12/01/2019'
        ]);

        $this->assertEquals('New Product Title', Product::first()->title);
        $this->assertEquals(2, Product::first()->company_id);
        $this->assertInstanceOf(Carbon::class, $product->fresh()->packagedOn);
        $this->assertEquals('2019/12/01', $product->fresh()->packagedOn->format('Y/m/d'));
        $response->assertRedirect($product->fresh()->path());
    }

    /** @test */
    public function destroyProduct()
    {
        $this->createStandardProduct();

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
    public function company_id_is_required()
    {
        $response = $this->post('/products', [
            'title' => 'Product Title',
            'company_id' => '',
            'packagedOn' => '01/11/2019'
        ]);

        $response->assertSessionHasErrors('company_id');
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

        $response = $this->getResponseCreateStandardProduct();

        $response->assertSessionHasNoErrors('packagedOn');
        $this->assertCount(1, Product::all());
        $this->assertInstanceOf(Carbon::class, Product::first()->packagedOn);
        $this->assertEquals('2019/11/01', Product::first()->packagedOn->format('Y/m/d'));
    }

    /** @test */
    public function aCompanyIsAutomaticallyAdded()
    {
        $this->withoutExceptionHandling();

        $this->post('/products', [
            'title' => 'Product Title',
            'company_id' => 'Brand Name',
            'packagedOn' => '11/01/2019'
            ]);

        $product = Product::first();
        $company = Company::first();

        $this->assertEquals($company->id, $product->company_id);
        $this->assertCount(1, Company::all());
    }    
      


      //////////////////////
     /// HELPER METHODS ///
    //////////////////////

    public function getResponseCreateStandardProduct()
    {
        $response = $this->post('/products', $this->getProductStandardAttributesAndValues());

        return $response;
    }

    public function createStandardProduct()
    {
        $this->post('/products', $this->getProductStandardAttributesAndValues());
    }

    public function getProductStandardAttributesAndValues()
    {
        return [
            'title' => 'Product Title',
            'packagedOn' => '11/01/2019',
            'company_id' => 1
        ];
    }
}
