<?php
namespace app\common\model;

class ExtProductBook extends Base {
    /**
     * 根据商品id获取图书信息
     */
    public static function getBookbyId($id,$fields=['book_manufacturer'])
    {
        $where['product_id'] = ['=',$id];
        return self::where($where)->field($fields)->find()->toArray();
    }
}