<?php

namespace app\api\controller;

use app\common\model\Category;
use app\common\model\CategoryDescription;
use app\common\model\Order;
use app\common\model\OrderProduct;
use app\common\model\Product;
use app\common\model\ProductDescription;
use app\common\model\ProductToCategory;
use think\Request;
class TeacherController extends BaseController
{
    /**
     * @author huxiaolei
     * 教师专区 首页
     * @param Request $request
     */
    public function getIndex(Request $request){
        //教师专区首页 名家公开课
        $final['mjgkk'] = self::getEachModule('名家公开课');
        //获取儿童阅读
        $final['etyd'] = self::getEachModule('儿童阅读');
        //获取传统文化教育
        $final['ctwhjy'] = self::getEachModule('传统文化教育');
        //获取通识教育
        $final['tsjy'] = self::getEachModule('通识教育');
        return $this->as_json($final);
    }
    /*
     * 获取名家公开课  儿童阅读  传统文化教育  通识教育 各个模块内容
     */
    protected function getEachModule($module_name = '')
    {
        $mjgkk_info = CategoryDescription::getByCategoryName($module_name);
        $mjgkk_category_id = $mjgkk_info->category_id;
        //所有属于名家公开课的子分类
        $mjgkk_child_category_info = Category::getByParentId($mjgkk_category_id);
        $mjgkk_child_category_ids = array_column($mjgkk_child_category_info, 'category_id');
        $mjgkk_product_ids_info = ProductToCategory::getProductIdByCategoryIds($mjgkk_child_category_ids);
        //名家公开课下所有的产品id
        $mjgkk_product_ids = array_column($mjgkk_product_ids_info, 'product_id');
        $mjgkk_product_names = ProductDescription::getNameByProductIds($mjgkk_product_ids);
        foreach ($mjgkk_product_names as $key => $item) {
            $t_product_id = $item['product_id'];
            $t_product_info = Product::getByProductId($t_product_id);
            $mjgkk_product_names[$key]['image'] = $t_product_info->image;
            $mjgkk_product_names[$key]['price'] = $t_product_info->price;
            $mjgkk_product_names[$key]['date_available'] = $t_product_info->date_available;
            $mjgkk_product_names[$key]['viewed'] = $t_product_info->viewed;
            $t_order_product = OrderProduct::getOrderIdByProductid($t_product_id);
            $t_order_ids = array_column($t_order_product, 'order_id');
            //报名人数
            $which_tag = '点灯人';//这里后续需要动态判断
            $mjgkk_product_names[$key]['signup_nums'] = Order::getNumsByOrderIds($t_order_ids, $which_tag);
        }
        return $mjgkk_product_names;
    }

    /**
     * @author huxiaolei
     * 各个模块点击“更多”时
     * @param Request $request
     */
    public function getEachModuleMore(Request $request){
        //名家公开课  儿童阅读  传统文化教育  通识教育; 默认获取“名家公开课”内容
        $module_name = $request->request('module_name', '名家公开课', 'trim');
        //默认选择直播课（直播课的分类id是80）
        $class_type = $request->request('class_type', 80, 'Intval');
        $info = CategoryDescription::getByCategoryName($module_name);
        $category_id = $info->category_id;
        //所有属于$module_name的子分类
        $child_category_info = Category::getByParentId($category_id);
        $child_category_ids = array_column($child_category_info, 'category_id');
        $child_category_name = CategoryDescription::getByCategoryIds($child_category_ids);
        //本身这个$module_name的分类
        $self_module_ary[0]['category_id'] = $category_id;
        $self_module_ary[0]['name'] = $module_name;
        //更多页面的 搜索框下面的 分类
        $final['category'] = array_merge($self_module_ary, $child_category_name);
        //直播课 录播课 有声书 案例
        $type_arry = ['直播课','录播课', '有声书','案例'];
        $class_type = [];//存储$type_arry的关系
        foreach ($type_arry as $item){
            //获取对应的分类id
            $t_des_info = CategoryDescription::getByCategoryName($item);
            $t_cate_id = $t_des_info->category_id;
            $class_type[$t_cate_id] = $item;
        }
        $final['class_type'] = $class_type;

        $module_product_ids_info = ProductToCategory::getProductIdByCategoryIds($child_category_ids);
        //$module_name所有的产品id
        $module_product_ids = array_column($module_product_ids_info, 'product_id');
        $class_type_product_ids_info  = ProductToCategory::getProductIdByCategoryIds($class_type);
        //直播课 录播课 有声书 案例这个分类下的所有产品id
        $class_type_product_ids = array_column($class_type_product_ids_info, 'product_id');

        //取交集
        $intersect_product_ids = array_intersect($module_product_ids, $class_type_product_ids);
        $content = [];//存储符合条件的结果
        if($intersect_product_ids){
            foreach ($intersect_product_ids as $t_product_id) {
                $t_mid_ary = [];
                $t_product_des_info = ProductDescription::getNameByProductIds($t_product_id);
                $t_mid_ary['name'] = $t_product_des_info[0]['name'];
                $t_product_info = Product::getByProductId($t_product_id);
                $t_mid_ary['image'] = $t_product_info->image;
                $t_mid_ary['price'] = $t_product_info->price;
                $t_mid_ary['date_available'] = $t_product_info->date_available;
                $t_mid_ary['viewed'] = $t_product_info->viewed;
                $t_order_product = OrderProduct::getOrderIdByProductid($t_product_id);
                $t_order_ids = array_column($t_order_product, 'order_id');
                //报名人数
                $which_tag = '点灯人';//这里后续需要动态判断
                $t_mid_ary['signup_nums'] = Order::getNumsByOrderIds($t_order_ids, $which_tag);
                $content[] = $t_mid_ary;
            }
        }
        $final['content'] = $content;
        //排序后续实现
        return $this->as_json($final);
    }
}
