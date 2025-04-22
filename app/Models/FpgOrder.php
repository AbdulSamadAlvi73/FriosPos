<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FpgOrder extends Model
{
    use HasFactory;

    protected $table = 'fpg_orders';
    protected $primaryKey = 'fgp_ordersID';
    protected $fillable = ['user_ID', 'fgp_item_id', 'unit_cost', 'unit_number', 'date_transaction', 'ACH_data', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID', 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(FpgItem::class, 'fgp_item_id', 'name');
    }
}
