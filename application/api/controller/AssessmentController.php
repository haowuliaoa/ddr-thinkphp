<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/11
 * Time: 13:58
 */
namespace app\api\controller;

use think\Config;
use think\Request as Request;
use app\common\model\Category;
use app\common\model\Setting;
use app\common\model\ProductToCategory;
use app\common\model\Product;
use app\common\model\ProductDescription;
use app\common\model\ExtProductBook;

class AssessmentController extends BaseController{
    public $book_type_key = 'config_store_book_type_category_id';
    /**
     * 阅读测评列表
     */
    public function index(Request $res) {
        $id = $res->get("id",62,'trim');                                            //阅读测评分类id
        $data['sort'] = $sort = $res->get("sort",0,'trim');                         //排序
        $data['filter_type'] =  $filter_type =  $res->get("filter_type",0,'trim');  //测评类别id
        //获取阅读评测列表
        $data['product_list'] = $this->getProductList($id,$sort,$filter_type);
        //获取阅读评测分类列表
        $data['filter_list'] = $this->getTypeList();
        return $this->as_json($data);
    }

    /**
     * 获取评测列表
     */
    private function getProductList($id,$sort_type,$filter_type){
        //筛选条件
        $res = [];
        switch ($sort_type){
            case 1:         //浏览人气
                $sort='viewed';
                $order='DESC';
                break;
            case 2:         //价格
                $sort='p.price';
                $order = 'ASC';
                break;
            case 3:         //评论
                $sort='( SELECT AVG(rating) AS total FROM oc_review r1 WHERE r1.product_id = p.product_id AND r1.STATUS = 1 GROUP BY r1.product_id )';
                $order='DESC';
                break;
            default:
                $sort='p.price';
                $order='ASC';
                break;
        }
        //获取筛选条件
        $filter_id = [];
        Config::get('');
        if($filter_type){
            $filter_id = array_column(ProductToCategory::getProductIdByCategoryIds($filter_type),'product_id');
        }
        $data = ['category_id'=>$id,'order'=>$order,'sort'=>$sort,'filter_id'=>$filter_id];
        //符合条件的评测商品
        $product_ids = ProductToCategory::getAssessmentIds($data);
        $product_ids = array_column($product_ids,'product_id');
        //商品详情
        if(!$product_ids){
            return $res;
        }
        $products = ProductDescription::getNameByProductIds($product_ids);
        foreach($products as $k=>$product){
            if(!$product){
                continue;
            }
            $product_id = $product['product_id'];
            $product_info = Product::getByProductId($product_id);
            $res[$k]['image'] = $product_info->image;
            $res[$k]['price'] = $product_info->price;
            $res[$k]['date_available'] = $product_info->date_available;
            $res[$k]['viewed'] = $product_info->viewed;
            $res[$k]['name'] = $product['name'];
            $res[$k]['publisher'] = ExtProductBook::getBookbyId($product_id)['book_manufacturer'];

        }
        return $res;
    }

    /**
     * 阅读测评的书目分类筛选
     * @return array
     */
    protected function getTypeList(){
        $book_type_category_id = Setting::getSettingByKey($this->book_type_key);
        $data[] = array(
            'category_id'=>0,
            'name'=>'全部',
        );
        $filter_categories = Category::getCategories($book_type_category_id);
        if($filter_categories){
            foreach ($filter_categories as $category) {
                $data[] = array(
                    'category_id'     => $category['category_id'],
                    'name'     => $category['name'],
                );
            }
        }
        return $data;
    }
}