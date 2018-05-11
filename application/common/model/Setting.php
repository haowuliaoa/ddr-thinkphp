<?php

namespace app\common\model;

use think\Cache;
use cache\Rediscache;
class Setting extends Base
{
    /**
     * 根据配置名获取配置项
     */
    public static function getSettingByKey($setting_name)
    {

        $where['key'] = ['=', $setting_name];
        return self::where($where)->value('value');
    }
}
