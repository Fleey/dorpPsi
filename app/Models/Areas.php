<?php

namespace App\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    use ModelTree, AdminBuilder;

    protected $fillable = ['parentid', 'name', 'sort'];

    protected $with = [
        'parent'
    ];

    public $table = 'areas';

    public $primaryKey = 'areaid';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // 父ID
        $this->setParentColumn('parentid');
        // 排序
        $this->setOrderColumn('sort');
        // 标题
        $this->setTitleColumn('name');
    }


    /**
     * 获取子节点
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child()
    {
        return $this->hasMany(get_class($this), 'parentid', $this->getKeyName());
    }


    /**
     * 获取父级节点
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent()
    {
        return $this->hasOne(get_class($this), $this->getKeyName(), 'parentid');
    }
}
