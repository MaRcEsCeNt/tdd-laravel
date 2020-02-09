<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Company;
use Carbon\Carbon;

class CompanyTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function storeCompany()
    {
        $this->withoutExceptionHandling();

        $this->post('/company', [
            'name' => 'Company Name',
            'dob' => '05/14/1988'
        ]);   

        $allCompanies = Company::all();

        $this->assertCount(1, $allCompanies);
        $this->assertInstanceOf(Carbon::class, $allCompanies->first()->dob);
        $this->assertEquals('1988/14/05', $allCompanies->first()->dob->format('Y/d/m'));
    }
}
