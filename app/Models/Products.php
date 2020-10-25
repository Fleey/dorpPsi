<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Products extends Model
{
    // 正常状态
    const PRODUCT_STATUS_NORMAL = 1;
    // 软删除状态
    const PRODUCT_STATUS_DELETE = 2;

    public $table = 'products';

    public $primaryKey = 'productid';
}
