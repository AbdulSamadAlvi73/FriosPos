<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FpgItem extends Model
{
    use HasFactory;

    protected $table = 'fpg_items'; // Ensure table name is correct
    protected $primaryKey = 'fgp_item_id'; // Explicitly define primary key
    public $timestamps = true; // Ensure timestamps are handled

    protected $fillable = [
        'category_ID', 
        'name', 
        'description', 
        'case_cost', 
        'internal_inventory', 
        'dates_available', 
        'image1', 
        'image2', 
        'image3', 
        'orderable'
    ];

    // Correct belongsTo relationship with FpgCategory
    public function category()
    {
        return $this->belongsTo(FpgCategory::class, 'category_ID', 'category_ID');
    }
}
