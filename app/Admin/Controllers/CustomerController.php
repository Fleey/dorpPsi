<?php

namespace App\Admin\Controllers;

use App\Models\Areas;
use App\Models\Customers;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CustomerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '客户中心';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Customers());

        $grid->model()
            ->where('userid', Admin::user()->id)
            ->where('status', '<>', Customers::CUSTOMER_STATUS_DELETE);

        $grid->column('customerid', __('Customerid'));
        $grid->column('name', '名称');
        $grid->column('phone', __('Phone'));
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
        $show = new Show(Customers::findOrFail($id));

        $show->field('customerid', __('Customerid'));
        $show->field('userid', __('Userid'));
        $show->field('name', __('Name'));
        $show->field('phone', __('Phone'));
        $show->field('areaid', __('Areaid'));
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
        $form = new Form(new Customers());

        $form->number('customerid', __('Customerid'));

        $form->text('name', '客户名称');
        $form->text('phone', __('Phone'));
        $form->select('areaid', '地区名称')->options(function ($id){
            $areas = Areas::find($id);

            if ($areas) {
                return [$areas->areaid => $areas->name];
            }
        })->ajax('/admin/api/areas/search', 'id', 'text');

        $form->textarea('address', '客户地址')->placeholder('请输入 客户地址 最多500个字符');

        return $form;
    }
}
