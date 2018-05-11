<?php
    /**
     * Created by PhpStorm.
     * User: huxiaolei
     */

    namespace app\common\behavior;

    use cache\Rediscache;

    class CacheBehavior
    {
        private $timeOut = 3600;
        #1 文件缓存 2 redis
        private $cacheType;

        public function __construct()
        {
          \Config('switch_cache') == 'redis' ?$this->cacheType = '2': $this->cacheType = 1;
        }

        public function setRedisData(&$params)
        {
            if($this->cacheType == 2){
                $key = $this->combinationCacheKey($params);
                if (is_object($params['data'])) {
                    //Rediscache::getInstance()->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
                    Rediscache::getInstance()->zAdd($this->combinationFuncKey($params), '', $params['func']);
                } else {
                    $params['data'] = json_encode($params['data']);
                }
                Rediscache::getInstance()->set($key, $params['data'], $this->timeOut);
            }
        }

        public function getRedisData(&$params)
        {
            if($this->cacheType == 2){
               // Rediscache::getInstance()->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
                $funcMap = Rediscache::getInstance()->zRange($this->combinationFuncKey($params), 0, -1);
                if (in_array($params['func'], $funcMap) && !empty($funcMap)) {
                    $data = Rediscache::getInstance()->get($this->combinationCacheKey($params));
                } else {
                    $data = json_decode(Rediscache::getInstance()->get($this->combinationCacheKey($params)), true);
                }
                if (!empty($data)) {
                    return $data;
                } else {
                    return false;
                }
            }
            return false;
        }

        public function combinationCacheKey($params)
        {
            $array = explode("\\", $params['class']);
            $class = end($array);
            $class_fun = explode("\\",$params['func']);
            $func = end($class_fun);
            return $class . ":".$func.":". $params['key'] . ":";
        }

        public function combinationFuncKey($params)
        {
            $array = explode("\\", $params['class']);
            $class = end($array);
            return $class . ":SERIALIZER_FUNCTION:";
        }

    }