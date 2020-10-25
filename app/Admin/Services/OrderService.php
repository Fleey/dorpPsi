<?php


namespace App\Admin\Services;


use Encore\Admin\Form;

class OrderService
{
    public function updateOrder(int $userId, Form $form)
    {
        $customerId = $form->customerid;
        $status     = $form->status;
        $remark     = $form->remark;

        if ($customerId == 0)
            return backError('参数过滤错误', '客户必须选择');
        if ($status != 1 && $status != 2)
            return backError('参数过滤错误', '订单状态必须选择正确');
        if (mb_strlen($remark) > 500)
            return backError('参数过滤错误', '订单备注不能超过500个字符');

        $orderModel = $form->model();

        $orderModel->userid       = $userId;
        $orderModel->total_amount = 0;

        return null;
    }

}
