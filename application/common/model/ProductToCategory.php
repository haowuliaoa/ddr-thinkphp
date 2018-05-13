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
    /**
     * 获取阅读评测商品列表
     */
    public static function getAssessmentIds($data,$fields = ['p.product_id'],$limit = 5,$offset=0)
    {
        $where['p.STATUS'] = ['=',1];
        $where['p.date_available'] = ['<=',time()];
        $where['oc_product_to_category.category_id'] = ['=',$data['category_id']];
        if($data['filter_id']){
            $where['p.product_id'] = ['in',$data['filter_id']];
        }
        return self::join('oc_product p', 'oc_product_to_category.product_id = p.product_id','LEFT')->where($where)->order($data['sort'],$data['order'])->order('p.sort_order','ASC')->field($fields)->limit($offset,$limit)->select()->toArray();
    }
}
