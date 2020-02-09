<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function store()
    {      
        Company::create(request()->only([
            'name', 'dob'
        ]));
    }
}
