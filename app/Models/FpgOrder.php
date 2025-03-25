<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FpgOrder extends Model
{
    use HasFactory;

    protected $table = 'fpg_orders';
    protected $primaryKey = 'fgp_ordersID';

    protected $fillable = [
        'user_ID',
        'fgp_item_id',
        'unit_cost',
        'unit_number',
        'date_transaction',
        'ACH_data',
        'status',
    ];
}
