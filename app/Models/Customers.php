<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    // 正常状态
    const CUSTOMER_STATUS_NORMAL = 1;
    // 软删除状态
    const CUSTOMER_STATUS_DELETE = 2;

    public $table = 'customers';

    public $primaryKey = 'customerid';

    public function orders()
    {
        return $this->belongsTo(Orders::class);
    }
}
