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

    // Many-to-many relationship with FpgCategory
    public function categories()
    {
        return $this->belongsToMany(FpgCategory::class, 'fpg_category_fpg_item', 'fgp_item_id', 'category_ID');
    }
    
}
