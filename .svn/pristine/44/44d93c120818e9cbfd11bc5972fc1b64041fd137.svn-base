<?php

namespace app\common\model;

use think\Cache;
use cache\Rediscache;
class CategoryDescription extends Base
{
    /**
     * 根据分类名称获取记录
     * @param string $name
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getByCategoryName($name = '', $fields = ['category_id'])
    {
        return self::where(['name' => $name])->field($fields)->find();
    }

    /**
     * 获取分类名称
     * @param array $category_ids
     * @param array $fields
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getByCategoryIds($category_ids = [], $fields = ['category_id', 'name'])
    {
        $where['category_id'] = ['in', $category_ids];
        $where['language_id'] = 2;
        return self::where($where)->field($fields)->select()->toArray();
    }
}
