<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FpgCategory extends Model
{
    use HasFactory;

    protected $table = 'fpg_categories';
    protected $primaryKey = 'category_ID';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'type'
    ];

    // Relationship with FpgItem
    public function items()
    {
        return $this->hasMany(FpgItem::class, 'category_ID', 'category_ID');
    }
}
