<?php


namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Products;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\MessageBag;

class ProductController extends Controller
{
    /**
     * 显示商品列表 页面
     * @param Content $content
     * @return Content
     */
    public function getListPage(Content $content)
    {
        $content->title('商品列表');

        $content->breadcrumb(
            ['text' => '商品列表']
        );

        $grid = new Grid(new Products);

        //过滤条件
        $grid->model()
            ->where('userid', Admin::user()->id)
            ->where('status', '<>', Products::PRODUCT_STATUS_DELETE);

        $likeProductName = $this->request->get('name');

        if (!empty($likeProductName)) {
            $grid->model()->where('name', 'like', '%' . $likeProductName . '%');
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


        return $content->body($grid);
    }

    /**
     * 创建商品表单页面
     * @param Content $content
     * @return Content
     */
    public function createProductPage(Content $content)
    {
        $content->title('新增商品');

        $content->breadcrumb(
            ['text' => '新增商品']
        );

        $form = new Form(new Products);

        $form->setTitle('添加商品');

        $form->text('name', '商品名称')->rules('required|max:128')->placeholder('请输入商品名称 注意商品名称不能重复');
        $form->text('unit', '计量单位')->rules('required|max:8')->placeholder('例如 个');
        $form->text('price', '商品价格')->rules('required|float')->placeholder('例如 16.15');

        $form->submitted(function (Form $form){
            exit(var_dump(2333));
        });

        $form->saving(function (Form $form) {

            $model = $form->model();

            $model->name  = 'name';
            $model->unit  = 'unit';
            $model->price = 'price';

            // 跳转页面
            return redirect('/admin/users');

        });

        $content->body($form);

        return $content;
    }

    /**
     * 创建商品
     * @return \Illuminate\Http\Response
     */
    public function createData()
    {
        $name  = $this->request->post('name');
        $unit  = $this->request->post('unit');
        $price = $this->request->post('price');

        if (empty($name))
            return error('商品名称不能为空');
        if (empty($unit))
            return error('商品单位不能为空');
        if (empty($price))
            return error('商品价格不能为空');
        if (mb_strlen($name) > 128)
            return error('商品名称不能超过128个字符');
        if (mb_strlen($unit) > 8)
            return error('商品单位不能超过8个字符');

        $price = floatval($price);
        if ($price <= 0)
            return error('商品价格不能为 零 或 负数');

        if ($price > 999999)
            return error('商品单价不能超过 999999 元');

        $price  = intval($price * 100);
        $time   = time();
        $userid = Admin::user()->id;

        $productModel = new Products();

        $ret = $productModel->newQuery()->where([
            'userid' => $userid,
            'name'   => $name
        ])->value('productid');

        if (!empty($ret))
            return error('商品名称已经有存在的');

        $ret = $productModel->newQuery()->insertGetId([
            'userid'      => $userid,
            'name'        => $name,
            'unit'        => $unit,
            'price'       => $price,
            'status'      => Products::PRODUCT_STATUS_NORMAL,
            'create_time' => $time,
            'update_time' => $time
        ]);

        if ($ret == 0)
            return error('创建商品失败，请重试');

        return json([
            'status'  => true,
            'message' => '创建成功',
            'id'      => $ret
        ]);
    }

    /**
     * 更新商品表字段
     * @param int $productId
     * @return \Illuminate\Http\Response
     */
    public function updateField(int $productId)
    {
        $whiteFields = ['name', 'unit', 'price'];

        $updateName  = $this->request->post('name');
        $updateValue = $this->request->post('value');

        if (empty($updateName) || empty($updateValue))
            return error('参数传递错误');

        if (!in_array($updateName, $whiteFields))
            return error('无法修改此参数');

        $productModel = new Products();

        $updateRet = $productModel->newQuery()->where([
            'productid' => $productId,
            'userid'    => Admin::user()->id
        ])->update([
            $updateName => $updateValue
        ]);

        if ($updateRet)
            return json('修改成功');
        else
            return error('修改失败');
    }

}
