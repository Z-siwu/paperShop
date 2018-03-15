<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderGood extends Model
{
    //
    protected $table = 'order_goods';

    public function order()
    {
        return $this->belongsTo(Order::class,'oid');
    }
}
