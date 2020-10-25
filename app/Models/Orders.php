<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{

    public $table = 'orders';

    public $primaryKey = 'orderid';

    public function customer()
    {
        return $this->hasOne(Customers::class,'customerid','customerid');
    }
}
