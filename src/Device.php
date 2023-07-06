<?php
/*
 * @author: 布尔
 * @name: 设备管理
 * @desc: 介绍
 * @LastEditTime: 2022-04-07 09:48:18
 */
namespace Eykj\Modian;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use Eykj\Modian\Service;
use function Hyperf\Support\env;

class Device
{
    
    #[Inject]
    protected GuzzleHttp $GuzzleHttp;
    
    #[Inject]
    protected Service $Service;
    /**
     * @author: 布尔
     * @name: 查询设备列表
     * @param array $param
     * @return array
     */
    public function getAllDeviceInfo(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/device/getAllDeviceInfo?accessToken=' . $access_token;
        return $this->GuzzleHttp->get($url);
    }
    /**
     * @author: 布尔
     * @name: 获取设备id
     * @param array $param
     * @return array
     */
    public function deviceId(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/device/deviceId?accessToken=' . $access_token . '&deviceSn=' . $param['deviceSn'];
        return $this->GuzzleHttp->get($url);
    }
    /**
     * @author: 布尔
     * @name: 开放平台更新设备三方鉴权配置
     * @param array $param
     * @return array 
     */
    public function updateCallbackConfig(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/device/updateCallbackConfig?accessToken=' . $access_token;
        $data['deviceId'] = $param['device_id'];
        $data['callbackSwitch'] = $param['callback_switch'];
        $data['callbackUrl'] = $param['callback_url'];
        $data['callbackAction'] = $param['callback_action'];
        $data['callbackSecretKey'] = $param['device_secret_key'];
        $data['callbackOfflineSwitch'] = $param['callback_offline_switch'];
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name: 查询设备业务配置信息
     * @param array $param
     * @return array 
     */
    public function getBizConfig(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/device/updateCallbackConfig?accessToken=' . $access_token . '&deviceId=' . $param['device_id'];
        return $this->GuzzleHttp->get($url);
    }
}