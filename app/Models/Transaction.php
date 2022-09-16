<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'uuid',
        'status',
        'reference',
        'url',
        'gateway',
        'requestId'
    ];

    /**
    * Relationship with order
    */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
