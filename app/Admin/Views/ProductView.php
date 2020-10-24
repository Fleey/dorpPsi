<?php


namespace App\Admin\Views;

use App\Models\Products;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class ProductView
{
    /**
     * 获取商品列表
     * @param int $userid
     * @param array $params
     * @return Grid
     */
    public function getProductList(int $userid, array $params = [])
    {

        $grid = new Grid(new Products);

        //过滤条件
        $grid->model()
            ->where('userid', $userid)
            ->where('status', '<>', Products::PRODUCT_STATUS_DELETE);

        if (isset($params['productName']) && $params['productName'] != '') {
            $grid->model()->where('name', 'like', '%' . $params['productName'] . '%');
        }

        $grid->column('productid', '商品ID')->sortable();
        $grid->column('name', '名称')->editable();
        $grid->column('unit', '单位')->editable();
        $grid->column('price', '价格')->display(function ($released) {
            return $released ? ($released / 100) : 0;
        })->sortable()->editable();
        $grid->column('create_time', '创建时间')->display(function ($released) {
            return date('Y-m-d H:i:s', $released);
        });
        $grid->column('update_time', '更新时间')->display(function ($released) {
            return date('Y-m-d H:i:s', $released);
        });


        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableView();
        });

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', '商品名称');
        });

        return $grid;
    }

    /**
     * 获取商品表单
     * @param string $formTitle
     * @param array $params
     * @return Form
     */
    public function getProductForm(string $formTitle = '添加商品', array $params = [])
    {
        $form = new Form(new Products);

        $form->setTitle($formTitle);

        $form->text('name', '商品名称')->value($params['name'] ?? '')->rules('required|max:128')->placeholder('请输入商品名称 注意商品名称不能重复');
        $form->text('unit', '计量单位')->value($params['unit'] ?? '')->rules('required|max:8')->placeholder('例如 个');
        $form->text('price', '商品价格')->value($params['price'] ?? '')->rules('required|float')->placeholder('例如 16.15');

//        $form->saved()

        return $form;
    }
}
