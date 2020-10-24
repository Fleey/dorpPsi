<?php

namespace App\Admin\Controllers;

use App\Models\Orders;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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
        $grid->column('userid', __('Userid'));
        $grid->column('customerid', __('Customerid'));
        $grid->column('status', __('Status'));
        $grid->column('total_amount', __('Total amount'));
        $grid->column('remark', __('Remark'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show->field('userid', __('Userid'));
        $show->field('customerid', __('Customerid'));
        $show->field('status', __('Status'));
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

        $form->number('userid', __('Userid'));
        $form->number('customerid', __('Customerid'));
        $form->switch('status', __('Status'));
        $form->number('total_amount', __('Total amount'));
        $form->text('remark', __('Remark'));

        return $form;
    }
}
