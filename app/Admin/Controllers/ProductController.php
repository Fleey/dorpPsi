<?php

namespace App\Admin\Controllers;

use App\Admin\Services\ProductService;
use App\Models\Products;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品列表';

    /**
     * @var int
     */
    protected $productId = 0;

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Products());

        $grid->model()
            ->where('userid', Admin::user()->id)
            ->where('status', '<>', Products::PRODUCT_STATUS_DELETE);

        $grid->column('productid', __('Productid'))->sortable();
        $grid->column('name', __('ProductName'));
        $grid->column('unit', __('Unit'));
        $grid->column('price', __('Price'))->sortable()->display(function ($value) {
            return $value / 100;
        });
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', __('ProductName'));
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
        $show = new Show(Products::findOrFail($id));

        $show->field('productid', __('Productid'));
        $show->field('userid', __('Userid'));
        $show->field('name', __('ProductName'));
        $show->field('unit', __('Unit'));
        $show->field('price', __('Price'))->as(function ($value) {
            return $value / 100;
        });
        $show->field('status', __('Status'))->as(function ($value) {
            return $value ? '正常' : '已删除';
        });
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
        $form = new Form(new Products);

        $form->text('name', __('ProductName'));
        $form->text('unit', __('Unit'))->placeholder('请输入计量单位，例如 元 只能两位小数');

        $form->text('price', '商品金额')->customFormat(function ($value) {
            return $value / 100;
        });

        $form->saving(function (Form $form) {
            $productService = new ProductService();
            $adminId        = Admin::user()->id;

            return $productService->updateProduct($adminId, $form);
        });

        return $form;
    }

    public function edit($id, Content $content)
    {
        $this->productId = $id;
        return parent::edit($id, $content);
    }

}
