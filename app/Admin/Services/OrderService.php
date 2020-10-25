<?php


namespace App\Admin\Services;


use App\Models\OrderInfo;
use App\Models\Orders;
use App\Models\Products;
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

    /**
     * 更新订单信息
     * @param Form $form
     * @param int $orderId
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function updateOrderInfo(Form $form, int $orderId)
    {
        $productId     = $form->productid;
        $totalNum      = intval($form->total_num);
        $discountPrice = $form->discount_price;

        if (empty($productId))
            return toastrError('商品必须选择');
        if ($totalNum <= 0)
            return toastrError('商品数量不能为零或负数');

        if ($form->isCreating() && $orderId != 0)
            $form->model()->orderid = $orderId;

        //需要人力金额补上金额
        if (!empty($discountPrice)) {
            $discountPrice = floatval(sprintf("%.2f", $discountPrice));
            if ($discountPrice < 0)
                return toastrError('商品金额不能为零');

            $form->discount_price = $discountPrice * 100;
            return null;
        }

        $productModel = new Products();
        $productPrice = $productModel->newQuery()->where('productid', $productId)->value('price');

        if (is_null($productPrice))
            return toastrError('商品不存在，请刷新页面重试');

        $form->discount_price = $productPrice * $totalNum;

        return null;
    }

    /**
     * 更新订单同金额
     * @param int $orderid
     */
    public function updateOrderTotalMoney(int $orderid)
    {
        $orderModel     = new Orders();
        $orderInfoModel = new OrderInfo();

        $orderTotalMoney = $orderInfoModel->newQuery()->where('orderid', $orderid)->sum('discount_price');

        $orderModel->newQuery()->where('orderid', $orderid)->update([
            'total_amount' => $orderTotalMoney
        ]);
    }

}
