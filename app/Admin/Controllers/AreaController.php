<?php

namespace App\Admin\Controllers;

use App\Admin\Services\AreaService;
use App\Models\Areas;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;
use Illuminate\Http\RedirectResponse;

class AreaController extends Content
{
    use HasResourceActions;

    private $updateId = 0;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户地区';

    /**
     * 首页
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content->title($this->title)
            ->description('列表')
            ->row(function (Row $row) {
                // 显示分类树状图
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('areas'));
                    $form->select('parentid', '父级地区')->options(Areas::selectOptions());
                    $form->text('name', '地区名称')->required();
                    $form->number('sort', '顺序')->default(99)->help('越小越靠前');
                    $form->hidden('_token')->default(csrf_token());
                    $column->append((new Box('新增地址', $form))->style('success'));
                });

            });
    }


    /**
     * 树状视图
     * @return Tree
     */
    protected function treeView()
    {
        return Areas::tree(function (Tree $tree) {
            $tree->disableCreate(); // 关闭新增按钮
            $tree->branch(function ($branch) {
                return "<strong>{$branch['name']}</strong>"; // 标题添加strong标签
            });
        });
    }

    /**
     * 详情页
     * @param $id
     * @return RedirectResponse
     */
    public function show($id)
    {
        return redirect()->route('categories', ['id' => $id]);
    }

    /**
     * 编辑
     * @param $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        $this->updateId = $id;

        return $content->title($this->title)
            ->description(__('edit'))
            ->row($this->form()->edit($id));
    }


    /**
     * 表单
     * @return Form
     */
    public function form()
    {
        $form = new Form(new Areas());

        $form->display('areaid', 'ID');
        $form->select('parentid', '父级地区')->options(Areas::selectOptions());
        $form->text('name', '地区名称')->required();
        $form->number('sort', '顺序')->default(99)->help('越小越靠前');

        $form->saving(function (Form $form) {
            $areaService = new AreaService();

            return $areaService->updateArea(Admin::user()->id, $form, $this->updateId);
        });

        return $form;
    }
}
