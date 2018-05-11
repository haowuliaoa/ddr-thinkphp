<?php

namespace app\common\model;

use think\Cache;
use cache\Rediscache;
class ProductToCategory extends Base
{
    /**
     * 获取分类集下的产品id集
     * @param array $category_ids
     * @param array $fields
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getProductIdByCategoryIds($category_ids = [], $fields = ['product_id'])
    {
        $where['category_id'] = ['in', $category_ids];
        return self::where($where)->field($fields)->select()->toArray();
    }
}
