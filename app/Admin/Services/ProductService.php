<?php


namespace App\Admin\Services;


use App\Models\Products;
use Encore\Admin\Form;
use Illuminate\Http\RedirectResponse;

class ProductService
{
    /**
     * 商品新增事件
     * @param int $adminId
     * @param Form $form
     * @return RedirectResponse | void
     */
    public function updateProduct(int $adminId, Form $form)
    {
        $productName  = $form->name;
        $productPrice = $form->price;
        $productUnit  = $form->unit;

        if (empty($productName))
            return backError('参数过滤错误', '商品名称必须填写');
        if (empty($productUnit))
            return backError('参数过滤错误', '计量单位必须填写');
        if (empty($productPrice))
            return backError('参数过滤错误', '商品金额不能为空');
        $productPrice = sprintf("%.2f", $productPrice);

        if (floatval($productPrice) <= 0)
            return backError('参数过滤错误', '商品金额不能小于等于零');

        if (mb_strlen($productName) > 128)
            return backError('参数过滤错误', '商品名称不能超过128个字符');

        if (mb_strlen($productUnit) > 8)
            return backError('参数过滤错误', '商品单位不能超过8个字符');

        //分为单位
        $form->price = $productPrice * 100;

        $productsModel = $form->model();

        if (!$form->isEditing())
            $productsModel->userid = $adminId;

        $productsModel->status = Products::PRODUCT_STATUS_NORMAL;
    }
}
