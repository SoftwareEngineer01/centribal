<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'catalog_id',
        'name',
    ];

    public function orders() {
        return $this->belongsTo(Order::class);
    }

}
