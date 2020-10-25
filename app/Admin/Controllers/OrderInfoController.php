<?php

namespace App\Admin\Controllers;

use App\Admin\Services\OrderService;
use App\Models\OrderInfo;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderInfoController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OrderInfo';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $orderId = session()->get('editOrderId');

        if (empty($orderId))
            return admin_error('请求数据异常', '请返回订单列表重新进入');


        $grid = new Grid(new OrderInfo());

        $grid->model()->where('orderid', $orderId);

        $grid->column('product.name', '商品名称');
        $grid->column('total_num', __('Total num'))->editable()->sortable();
        $grid->column('discount_price', '商品总金额')->sortable()->display(function ($value) {
            return $value / 100;
        })->editable();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->disableCreateButton();
        $grid->disableFilter();

        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $create->select('productid', '商品名称')
                ->options(admin_url('api/product/search'))
                ->ajax(admin_url('api/product/search'), 'id', 'text');
            $create->integer('total_num', '下单数量');
            $create->text('discount_price', '总计金额')->placeholder('请输入 总计金额 不填则自动计算');

        });

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit();
        });

        return $grid;
    }


    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(OrderInfo::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('orderid', __('Orderid'));
        $show->field('productid', __('Productid'));
        $show->field('total_num', __('Total num'));
        $show->field('discount_price', __('Discount price'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OrderInfo());

        $form->number('orderid', __('Orderid'));
        $form->number('productid', __('Productid'));
        $form->number('total_num', __('Total num'));
        $form->number('discount_price', __('Discount price'));

        $orderService = new OrderService();
        $orderId      = session()->get('editOrderId');

        $form->saving(function (Form $form) use ($orderService, $orderId) {
            return $orderService->updateOrderInfo($form, $orderId ?? 0);
        });

        $form->saved(function (Form $form) use ($orderService, $orderId) {
            $orderService->updateOrderTotalMoney($orderId);
        });

        $form->deleting(function (Form $form) use ($orderService, $orderId) {
            $orderService->updateOrderTotalMoney($orderId);
        });

        return $form;
    }
}
