<?php

namespace app\api\controller;

use app\common\model\User;
use think\Controller;
use think\Request;

class BaseController extends Controller
{
    protected $uid = 0;
    protected $region_id = 0;
    protected $school_id = 0;


    public function _initialize()
    {
        //$this->getUidByToken();
    }

    /**
     * @author huxiaolei
     * @date 2018-04-23
     */
    protected function getUidByToken()
    {
        $controller_name = Request::instance()->controller();
        $action = Request::instance()->action();
        $token = Request::instance()->param('token','','trim');
        $not_filter = ['User/postLogin'];
        $method = $controller_name . '/' . $action;
        if (!in_array($method, $not_filter)) {
            if (empty($token)) {
                _e(10001,'登录失败，请重新登录');
            }
            $this->uid = User::getUidByToken($token);
            if ($this->uid === -1) {
                _e(10001,'Token已失效，请重新登录获取');
            }elseif ($this->uid === 0) {
                _e(10001,'登录失败，请重新登录');
            }
        }
    }

    /**
     * @Author huxiaolei
     *
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return \think\response\Json
     */
    protected function as_json($code = 10000, $msg = 'ok', $data = [])
    {
        header('Content-Type:application/json; charset=utf-8');
        if (count(func_get_args()) == 1) {
            return json(['code' => 10000, 'msg' => 'ok', 'data' => $code]);
        } else {
            return json(['code' => $code, 'msg' => $msg, 'data' => $data]);
        }
    }
}
