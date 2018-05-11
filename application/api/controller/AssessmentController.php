<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/11
 * Time: 13:58
 */
namespace app\api\controller;

use think\Request as Request;
use app\common\model\Category;
use app\common\model\Setting;

class AssessmentController extends BaseController{
    public $book_type_key = 'config_store_book_type_category_id';

    /**
     * 阅读测评列表
     */
    public function index(Request $res) {
        $id = $res->query("tab_category_id",62,'trim'); //阅读测评分类id
        $sort = $res->query("sort",0,'trim');//排序
        $type =  $res->query("type",0,'trim');//测评类别id
        //获取阅读评测列表
        $data['product'] = $this->getList($id,$sort,$type);
        //获取阅读评测分类列表
        $data['filter_categories'] = $this->getTypeList();
        $data['sort'] = $sort;
        $data['type'] = $type;
        return $this->as_json($data);
    }

    /**
     * 获取评测列表
     */
    private function getList($id,$sort_type,$type){
        $sort='p.sort_order';
        $order='ASC';
        switch ($sort_type){
            case 1:
                $sort='viewed';//浏览人气
                $order='DESC';
                break;
            case 2:
                $sort='p.price';//价格
                break;
            case 3:
                $sort='rating';//评论
                $order='DESC';
                break;
            case 4:
                $sort='p.date_modified';//最新
                $order='DESC';
                break;
            default:
                break;
        }
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