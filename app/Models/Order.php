<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'date_order',
        'date_delivery',
        'customer_id',
    ];

    public $timestamps = false;

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
