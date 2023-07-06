<?php

/*
 * @author: 布尔
 * @name: 回调管理
 * @desc: 介绍
 * @LastEditTime: 2023-07-06 14:25:20
 */
namespace Eykj\Modian;

use Eykj\Base\GuzzleHttp;
use Eykj\Modian\Service;
use function Hyperf\Support\env;

class Callback
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?Service $Service;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp, ?Service $Service)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service = $Service;
    }
    /**
     * @author: 布尔
     * @name: 注册回调
     * @param array $param
     * @return array
     */
    public function addOrgCallback(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/callback/addOrgCallback?accessToken=' . $access_token;
        $data['callbackUrl'] = $param['callback_url'];
        $data['callbackTag'] = $param['callback_tag'];
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name: 修改回调
     * @param array $param
     * @return array 
     */
    public function updateOrgCallback(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/callback/updateOrgCallback?accessToken=' . $access_token;
        $data['callbackUrl'] = $param['callback_url'];
        $data['callbackTag'] = $param['callback_tag'];
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name: 删除回调
     * @param {array} $param
     * @return array
     */
    public function deleteOrgCallback(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/callback/deleteOrgCallback?accessToken=' . $access_token;
        $data = [];
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name:  文件回调接口
     * @param {array} $param
     * @return array
     */
    public function updateOrgCallbackFileTransfer(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/callback/updateOrgCallbackFileTransfer?accessToken=' . $access_token;
        $data['callbackFileTransferType'] = $param['callback_file_transfer_type'];
        $data['ossBucket'] = env('ALIYUN_ACM_BUCKET');
        $data['ossAccessKeyId'] = env('ALIYUN_ACM_AK');
        $data['ossSecretAccessKey'] = env('ALIYUN_ACM_SK');
        return $this->GuzzleHttp->post($url, $data);
    }
}