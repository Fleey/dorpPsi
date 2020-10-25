<?php

namespace App\Admin\Services;

use App\Models\Areas;
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
        $areaModel->status = Areas::AREA_STATUS_NORMAL;

        return null;
    }

    /**
     * 返回一个链路式地区名称
     * @param int $parentId
     * @param string $name
     * @return string
     */
    public function getAreaLinkStr(int $parentId, string $name)
    {
        $ret = Areas::query()->where('areaid', $parentId)->first(['name', 'parentid']);

        if ($ret['parentid'] != 0) {
            return $this->getAreaLinkStr($ret['parentid'], $ret['name'] . '/' . $name);
        } else {
            return $ret['name'] . '/' . $name;
        }
    }

    /**
     * 格式化查询地区数据
     *
     * @param array $data
     * @return array
     */
    public function formatSearchAreaData(array $data)
    {
        foreach ($data['data'] as &$content) {
            if ($content['parentid'] == 0) {
                $content = [
                    'id'   => $content['id'],
                    'text' => $content['text']
                ];
            } else {
                $content = [
                    'id'   => $content['id'],
                    'text' => $this->getAreaLinkStr($content['parentid'], $content['text'])
                ];
            }
        }

        return $data;
    }

    /**
     * 模糊查询地区信息
     * @param int $userId
     * @param string $searchName
     * @return array
     */
    public function selectSearchAreaInfo(int $userId, string $searchName)
    {
        $areaModel = new Areas();

        $selector = $areaModel->newQuery()->where('userid', $userId);

        $selector = $selector->where('status', '<>', Areas::AREA_STATUS_DELETE);

        $selector = $selector->where('name', 'like', '%' . $searchName . '%');

        $ret = $selector->paginate(null, ['areaid as id', 'name as text', 'parentid']);

        $ret = json_decode(json_encode($ret), true);

        return $this->formatSearchAreaData($ret);
    }
}
