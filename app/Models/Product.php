<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Property;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'images',
        'description',
        'brand_id',
        'count',
        'inventory_number',
        'total_number',
        'sales_number',
        'rate',
        'vote'
    ];


    public function brand(){
        return $this->belongsTo(Brands::class);
    }

    public function properties(){
        return $this->hasMany(ProductProperty::class);
    }
    public function media(){
        return $this->hasMany(Media::class);
    }
}
