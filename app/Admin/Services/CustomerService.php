<?php


namespace App\Admin\Services;


use App\Models\Customers;
use Encore\Admin\Form;

class CustomerService
{
    /**
     * 更新客户信息
     * @param int $userId
     * @param Form $form
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function updateCustomer(int $userId, Form $form)
    {
        $clientName = $form->name;
        $phone      = $form->phone;
        $areaId     = $form->areaid;
        $address    = $form->address;

        if (empty($clientName))
            return backError('参数过滤错误', '客户名称必须填写');
        if (empty($phone))
            return backError('参数过滤错误', '客户手机号码必须填写');
        if (empty($areaId))
            return backError('参数过滤错误', '客户地区必须选择');
        if (empty($address))
            return backError('参数过滤错误', '客户地址必须填写');

        if (mb_strlen($clientName) > 32)
            return backError('参数过滤错误', '客户名称不能超过32个字符');
        if (mb_strlen($phone) > 16)
            return backError('参数过滤错误', '客户手机号码不能超过16个字符');
        if (mb_strlen($address) > 500)
            return backError('参数过滤错误', '客户地址不能超过500个字符');

        $model         = $form->model();
        $model->userid = $userId;

        return null;
    }

    /**
     * 查询客户信息
     * @param int $userId
     * @param string $searchName
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchCustomerInfo(int $userId, string $searchName)
    {
        $customerModel = new Customers();
        $selector      = $customerModel->newQuery()->where('userid', $userId);

        $selector = $selector->where('status', '<>', Customers::CUSTOMER_STATUS_DELETE);

        $selector = $selector->where('name', 'like', '%' . $searchName . '%');

        $ret = $selector->paginate(null,['customerid as id', 'name as text']);


        return $ret;
    }
}
