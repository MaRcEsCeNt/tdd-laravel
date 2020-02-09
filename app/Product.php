<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    
    protected $dates = ['packagedOn'];

    public function path()
    {
        return '/products/' . $this->id;
    }

    public function setPackagedOnAttribute($datePackagedOn)
    {
        $this->attributes['packagedOn'] = Carbon::parse($datePackagedOn);
    }
}
