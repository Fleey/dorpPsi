<?php


namespace App\Admin\Controllers;


use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;

class OrderPrintController extends AdminController
{
    protected $title = '订单详细资料';

    protected function grid()
    {
        return view('Order/OrderPrintPage', [
            'csrf' => csrf_token()
        ]);
    }
}
