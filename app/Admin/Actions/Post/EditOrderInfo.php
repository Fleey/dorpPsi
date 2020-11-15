<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class EditOrderInfo extends RowAction
{
    public $name = '编辑订单信息';

    public function handle(Model $model)
    {
        // $model ...
        $orderId = $model->orderid;


        return $this->response()->redirect('/admin/orders/' . $orderId . '/info');
    }

}
