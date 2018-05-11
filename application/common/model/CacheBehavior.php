<?php
    /**
     * Created by PhpStorm.
     * User: huxiaolei
     */

    namespace app\common\model;

    use cache\Rediscache;

    class CacheBehavior
    {
        private $timeOut = 3600;

        public function setRedisData(&$params)
        {
            $key = $this->combinationCacheKey($params);
            if (is_object($params['data'])) {
                Rediscache::getInstance()->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
                Rediscache::getInstance()->zAdd($this->combinationFuncKey($params),'',$params['func']);
            }else{
                $params['data'] = json_encode($params['data']);
            }
            Rediscache::getInstance()->set($key, $params['data'], $this->timeOut);
        }

        public function getRedisData(&$params)
        {
            $funcMap = Rediscache::getInstance()->zRange($this->combinationFuncKey($params),0,-1);
            if(in_array($params['func'],$funcMap) && !empty($funcMap)){
                $data = Rediscache::getInstance()->get($this->combinationCacheKey($params));
            }else{
                $data = json_decode(Rediscache::getInstance()->get($this->combinationCacheKey($params)),true);
            }
            if(!empty($data)){
                return $data;
            }else{
                return false;
            }

        }

        public function combinationCacheKey($params)
        {
            $array = explode("\\", $params['class']);
            $class = end($array);
            return $class . ":" . $params['key'] . ":";
        }

        public function combinationFuncKey($params){
            $array = explode("\\", $params['class']);
            $class = end($array);
            return $class . ":SERIALIZER_FUNCTION:";
        }

    }