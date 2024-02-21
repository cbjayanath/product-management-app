<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected  $fillable = [
        'name',
        'code',
        'image',
        'description',
        'category_id',
        'display_order_no',
        'price',
        'created_by'
    ];
    public function category()
    {
        return $this->hasOne(Category::class,'id','category_id');
    }
    public function creator()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
}
