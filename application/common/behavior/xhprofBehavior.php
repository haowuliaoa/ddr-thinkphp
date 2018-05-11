<?php
    /**
     * Created by PhpStorm.
     * User: huxiaolei
     */

    namespace app\common\behavior;


    class xhprofBehavior
    {

        static protected $time;
        public function appInit(&$params)
        {
            self::$time = microtime(true);
            #防止notice错误
            error_reporting(E_ERROR);
            xhprof_enable();
        }

        public function appEnd(&$params)
        {
            $nowTime = microtime(true);
            $diff =  ceil($nowTime -  self::$time);
            if($diff > 1){
                $url = explode('/',$_SERVER['REQUEST_URI']);
                $xhprof_data = xhprof_disable();
                include_once   "../../../xhprof/xhprof_lib/utils/xhprof_lib.php";
                include_once   "../../../xhprof/xhprof_lib/utils/xhprof_runs.php";;
                $xhprof_runs = new \XHProfRuns_Default();
                $xhprof_runs->save_run($xhprof_data, $url[1].'_'.$url[2].'_'.$url[3]);
            }
        }
    }