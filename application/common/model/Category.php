<?php

namespace app\common\model;

use think\Cache;
use cache\Rediscache;
class Category extends Base
{
    /**
     * 获取父分类下的子分类集
     * @param int $parent_id
     * @param array $fields
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getByParentId($parent_id = 0, $fields = ['category_id'])
    {
        return self::where(['parent_id' => $parent_id])->field($fields)->select()->toArray();
    }

    /**
     * 阅读测评的书目分类筛选
     */
    public static function getCategories($id)
    {
        $where['oc_category.parent_id'] = ['=',$id];
        $where['oc_category_description.language_id'] = ['=',2];
        $where['oc_category_to_store.store_id'] = ['=',0];
        $where['oc_category.STATUS'] = ['=',1];
        $fields = ['oc_category_description.name','oc_category.category_id'];
        return self::join('oc_category_description', 'oc_category.category_id = oc_category_description.category_id','LEFT')
            ->join('oc_category_to_store','oc_category.category_id  = oc_category_to_store.category_id','LEFT')
            ->where($where)->field($fields)->order('oc_category.sort_order')->select()->toArray();
    }
}
