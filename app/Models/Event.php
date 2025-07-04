<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(Customer::class , 'customer_id' , 'customer_id');
    }
}
