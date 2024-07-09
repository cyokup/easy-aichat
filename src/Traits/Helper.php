<?php

/*
 * This file is part of the overtrue/easy-sms.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cyokup\EasyAiChat\Traits;

trait Helper
{
    /**
     * 发送CURL请求
     * @param $url   请求的URL
     * @param $method   请求方法
     * @param $params  参数（关联数组形式）
     * @param array $header 一维数组的请求头信息（非关联数组）。
     * @return bool
     */
    function httpCurl($url, $method = 'GET', $params = [], $header = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //将获取的信息以字符串返回，而不是直接输出
        curl_setopt($ch, CURLOPT_URL, $method == "POST" ? $url : $url . '?' . http_build_query($params));  //http_build_query数组转Url格式参数
        //设置超时时间
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        //如果是https协议，取消检测SSL证书
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            //CURL_SSLVERSION_TLSv1
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        }
        //判断是否设置请求头
        if (count($header) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        //通过POST方式提交
        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        $data = curl_exec($ch);   //执行curl操作
        if ($data === false) {
            $data = curl_error($ch);
        }
        curl_close($ch);   //关闭curl操作
        return $data;
    }

}
