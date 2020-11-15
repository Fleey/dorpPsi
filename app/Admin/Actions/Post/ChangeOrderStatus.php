<?php

namespace App\Admin\Actions\Post;

use App\Models\Orders;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class ChangeOrderStatus extends RowAction
{
    public $name = '改变支付状态';

    public function handle(Model $model)
    {
        // $model ...
        $orderId = $model->orderid;

        $orderModel = new Orders();

        $orderStatus = $orderModel->newQuery()->where('orderid', $orderId)->value('status');

        if ($orderStatus === 1)
            $orderStatus = 2;
        else
            $orderStatus = 1;

        $orderModel->newQuery()->where('orderid', $orderId)->update([
            'status' => $orderStatus
        ]);

        return $this->response()->success('修改订单状态成功.')->refresh();
    }

}
