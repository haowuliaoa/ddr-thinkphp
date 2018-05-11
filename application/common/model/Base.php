<?php

namespace app\common\model;

use cache\Rediscache;
use think\Cache;
use think\Hook;
use think\Model;

class Base extends Model
{
    const CRYPT_KEY = 'qjmy7123';

    protected $resultSetType = 'collection';

    //缓存相关
    protected static function setCache($cache_key, $cache_content, $cache_time = 3600 * 8){
        if(\Config('switch_cache') == 'redis'){
            $cache_key['data'] = $cache_content;
            Hook::listen('set_redis_data',$cache_key);
        }else{
            $cache_key = implode('.',$cache_key);
            Cache::set($cache_key, $cache_content, $cache_time);
        }
    }

    protected static function getCache($cache_key){
        $res = [];
        if(\Config('switch_cache') == 'redis'){
            $res = Hook::listen('get_redis_data',$cache_key)[0];
        }else{
            $cache_key = implode('.',$cache_key);
            $res = Cache::get($cache_key);
        }
        return $res;
    }

}
