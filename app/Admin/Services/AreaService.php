<?php

namespace App\Admin\Services;

use Encore\Admin\Form;

class AreaService
{
    /**
     * 更新地区信息
     * @param int $adminId
     * @param Form $form
     * @param int $updateId
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function updateArea(int $adminId, Form $form, int $updateId = 0)
    {
        $parentId = $form->parentid;
        $name     = $form->name;
        $sort     = intval($form->sort);

        if ($sort > 255)
            return backError('参数过滤错误', '顺序不能超过255');
        if ($sort < 0)
            return backError('参数过滤错误', '顺序不能低于零');

        if (empty($name))
            return backError('参数过滤错误', '地区名称必须填写');
        if (mb_strlen($name) > 64)
            return backError('参数过滤错误', '地区名称不能超过64个字符');


        $areaModel = $form->model();

        $selector = $areaModel->newQuery()->where([
            'userid' => $adminId,
            'name'   => $name
        ]);

        $ret = $selector->value('areaid');

        if ($updateId == 0) {
            //创建状态
            if (!empty($ret))
                return backError('地区名称重复', '地区名称不能够重复，请更换其他地区名称');
        } else {
            //更新状态
            if ($ret != $updateId)
                return backError('地区名称重复', '地区名称不能够重复，请更换其他地区名称');
        }

        $areaModel->userid = $adminId;

        return null;
    }
}
