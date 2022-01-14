<?php

namespace App\Models;

use App\Models\Article;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
