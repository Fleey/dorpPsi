<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    public $table = 'order_info';

    public $primaryKey = 'id';

    public function product()
    {
        return $this->hasOne(Products::class,'productid','productid');
    }
}
