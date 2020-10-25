<?php

namespace App\Admin\Controllers;

use App\Admin\Services\AreaService;
use App\Admin\Services\OrderService;
use App\Models\Areas;
use App\Models\Customers;
use App\Models\Orders;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new Orders());

        $grid->column('orderid', __('Orderid'));

        $grid->column('customer.name', '客户名称');

        $grid->column('status', '订单状态')->display(function ($value) {
            if ($value == 1)
                return '已付款';
            else if ($value == 2)
                return '未付款';
            else
                return '未知状态';
        });
        $grid->column('total_amount', __('Total amount'))->display(function ($value) {
            return $value / 100;
        });
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));


        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();

            $filter->getModel()->where('userid', Admin::user()->id);

            $filter->like('orderid', '订单ID');
            $filter->like('customer.name', '客户名称');

            $filter->equal('status', '订单状态')->select([
                1 => '已付款',
                2 => '未付款'
            ]);

            $filter->between('created_at', '创建时间')->datetime();
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
        $show = new Show(Orders::findOrFail($id));

        $show->field('orderid', __('Orderid'));
        $show->field('customerid', __('Customerid'))->as(function ($value) {
            $data = Customers::find($value);

            return $data ? $data->name : '未知用户';
        });
        $show->field('status', __('Status'))->as(function ($value) {
            if ($value == 1) {
                return '已支付';
            } else if ($value == 2) {
                return '未支付';
            } else {
                return '未知状态';
            }
        });
        $show->field('total_amount', __('Total amount'));
        $show->field('remark', __('Remark'));
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
        $form = new Form(new Orders());

        $form->setTitle('创建订单主体');

        $form->select('customerid', '客户名称')->options(function ($id) {

            $data = Customers::find($id);

            if ($data) {
                return [
                    $data->customerid => $data->name
                ];
            }
        })->ajax('/admin/api/customer/search', 'id', 'text');

        $form->select('status', '订单状态')->options(function ($id) {
            return [
                1 => '已支付',
                2 => '未支付'
            ];
        });

        $form->textarea('remark', __('Remark'));

        $form->text('total_amount', __('订单总金额'))->value('0 元')->disable();

        $form->saving(function (Form $form) {
            $orderService = new OrderService();

            return $orderService->updateOrder(Admin::user()->id, $form);
        });

        $form->saved(function (Form $form) {
            if (!$form->isCreating())
                return null;

            $orderId = $form->model()->orderid;

            // 跳转页面
            return redirect(admin_url('order_info?orderid=' . $orderId));
        });

        return $form;
    }
}
