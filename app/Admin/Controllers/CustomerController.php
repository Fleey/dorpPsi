<?php

namespace App\Admin\Controllers;

use App\Admin\Services\AreaService;
use App\Admin\Services\CustomerService;
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

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->getModel()->where('userid', Admin::user()->id);

            $filter->like('name', '客户名称');
            $filter->like('phone', '联系电话');
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
        $show = new Show(Customers::findOrFail($id));

        $show->field('name', '客户名称');
        $show->field('phone', __('Phone'));
        $show->field('areaid', '地区名称')->as(function ($value) {
            $areaService = new AreaService();

            $data = Areas::find($value);

            if ($data) {
                return $areaService->getAreaLinkStr($data->parentid, $data->name);
            } else {
                return '此地区已被删除';
            }
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
        $form = new Form(new Customers());

        $form->text('name', '客户名称');
        $form->text('phone', __('Phone'));
        $form->select('areaid', '地区名称')->options(function ($id) {
            $areaService = new AreaService();

            $data = Areas::find($id);

            if ($data) {
                return [
                    $data->areaid => $areaService->getAreaLinkStr($data->parentid, $data->name)
                ];
            }
        })->ajax('/admin/api/areas/search', 'id', 'text');

        $form->textarea('address', '客户地址')->placeholder('请输入 客户地址 最多500个字符');


        $form->saving(function (Form $form) {
            $customerService = new CustomerService();

            return $customerService->updateCustomer(Admin::user()->id, $form);
        });

        return $form;
    }

    /**
     * 查询客户信息
     * @param CustomerService $customerService
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchCustomerInfo(CustomerService $customerService)
    {
        $queryName = \request()->request->get('q','');

        if (empty($queryName))
            $queryName = '';

        $adminId = Admin::user()->id;

        $ret = $customerService->searchCustomerInfo($adminId, $queryName);

        return $ret;
    }
}
