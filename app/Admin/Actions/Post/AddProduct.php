<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class AddProduct extends RowAction
{
    public $name = '查看详细';

    public function handle(Model $model)
    {
        // $model ...


        Session::put('editOrderId',$model->orderid);
        return $this->response()->redirect(admin_url('order_infos?orderid=' . $model->orderid));
    }

}
