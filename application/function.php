<?php
/**
 * 输出信息
 * @param int $status
 * @param string $message
 * @param array $data
 * @param bool $is_log
 */
/**
 * 版本号
 */
const VERSION = '1.0';

function _e($status = 1, $message = "", $data = [], $is_log = true)
{
    $data['code'] = $status;
    $data['msg'] = $message;
    $data['data'] = $data;
    header('Content-Type:application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

?>