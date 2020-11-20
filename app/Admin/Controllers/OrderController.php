<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\AddProduct;
use App\Admin\Actions\Post\ChangeOrderStatus;
use App\Admin\Actions\Post\EditOrderInfo;
use App\Admin\Services\OrderService;
use App\Models\Areas;
use App\Models\Customers;
use App\Models\Orders;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
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

        $grid->column('customers.name', '客户名称');

        $grid->column('customers.areaid', '地区')->display(function ($value){
            return Areas::query()->where('areaid',$value)->value('name');
        });

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

        $grid->actions(function ($actions) {
            $actions->add(new ChangeOrderStatus());

            $actions->add(new EditOrderInfo());

            // 去掉编辑
            $actions->disableEdit();

            // 去掉查看
            $actions->disableView();
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
        $show->field('total_amount', __('Total amount'))->as(function ($value) {
            return $value ? ($value / 100) : 0;
        });
        $show->field('remark', __('Remark'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * 创建订单页面
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        Admin::js('/js/Order/CreateOrderPage.js');
        return $content
            ->title('创建订单')
            ->description('创建')
            ->body(view('Order/CreateOrderPage', [
                'csrfToken' => csrf_token()
            ]));
    }

    public function createOrder(OrderService $orderService)
    {
        $request = request();

        $customerid  = $request->post('customerid');
        $productList = $request->post('productList');
        $createTime  = $request->post('createTime');

        if (empty($customerid))
            return response()->json(['status' => false, 'msg' => '必须选择客户信息']);
        if (!is_array($productList))
            return response()->json(['status' => false, 'msg' => '商品列表格式不正确']);
        if (count($productList) <= 0)
            return response()->json(['status' => false, 'msg' => '订单商品数量不能为空']);

        if (empty($createTime))
            $createTime = time();
        else
            $createTime = strtotime($createTime);

        foreach ($productList as $content) {
            if (empty($content['price']))
                return response()->json(['status' => false, 'msg' => '商品价格不能为零']);
            if (empty($content['discountPrice']))
                return response()->json(['status' => false, 'msg' => '商品总金额不能为空']);
            if (empty($content['count']))
                return response()->json(['status' => false, 'msg' => '商品数量不能为空']);
            if (empty($content['productid']))
                return response()->json(['status' => false, 'msg' => '商品必须选择']);
        }

        $orderService->createOrderInfo($customerid, $productList, $createTime);

        return response()->json(['status' => true, 'msg' => '创建成功']);
    }

    public function updateOrderInfo(int $orderid, OrderService $orderService)
    {
        $request = request();

        $customerid  = $request->post('customerid');
        $productList = $request->post('productList');

        if (empty($customerid))
            return response()->json(['status' => false, 'msg' => '必须选择客户信息']);
        if (!is_array($productList))
            return response()->json(['status' => false, 'msg' => '商品列表格式不正确']);
        if (count($productList) <= 0)
            return response()->json(['status' => false, 'msg' => '订单商品数量不能为空']);

        foreach ($productList as $content) {
            if (empty($content['price']))
                return response()->json(['status' => false, 'msg' => '商品价格不能为零']);
            if (empty($content['discountPrice']))
                return response()->json(['status' => false, 'msg' => '商品总金额不能为空']);
            if (empty($content['count']))
                return response()->json(['status' => false, 'msg' => '商品数量不能为空']);
            if (empty($content['productid']))
                return response()->json(['status' => false, 'msg' => '商品必须选择']);
        }

        $orderService->updateOrderInfo($orderid, $customerid, $productList);

        return response()->json(['status' => true, 'msg' => '修改成功']);
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

        $form->text('total_amount', __('订单总金额'))->value('0 元')->customFormat(function ($value) {
            return $value ? ($value / 100) : 0;
        })->disable();

        $form->saving(function (Form $form) {
            $orderService = new OrderService();

            return $orderService->updateOrder(Admin::user()->id, $form);
        });

        $form->saved(function (Form $form) {
            if (!$form->isCreating())
                return null;

            $orderId = $form->model()->orderid;

            session('editOrderId', $orderId);

            // 跳转页面
            return redirect(admin_url('order_infos?orderid=' . $orderId));
        });

        return $form;
    }

    /**
     * 获取客户订单列表
     * @param int $customerid
     * @param OrderService $orderService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerOrderList(int $customerid, OrderService $orderService)
    {
        $request    = request();
        $createTime = $request->get('createTime') ?? '';
        $endTime    = $request->get('endTime') ?? '';

        return response()->json([
            'status' => true,
            'data'   => $orderService->getCustomerOrderProductList($customerid, $createTime, $endTime)
        ]);
    }
}
