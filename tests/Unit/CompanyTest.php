<?php

namespace Tests\Unit;

use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_name_is_required_to_create_company()
    {   
        Company::firstOrCreate([
            'name' => 'Company Name',
        ]);

        $this->assertCount(1, Company::all());
    }
}
