<?php

/*
 * @author: 布尔
 * @name: 访客
 * @desc: 介绍
 * @LastEditTime: 2023-07-06 14:27:06
 */
namespace Eykj\Modian;

use Eykj\Base\GuzzleHttp;
use Eykj\Modian\Service;
use function Hyperf\Support\env;

class Visitor
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
     * @name: 创建访客预约
     * @param array $param
     * @return array
     */
    public function appointment_create(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/appointment/create?accessToken=' . $access_token;
        $data = eyc_array_key($param, 'visitorName,visitorMobile,visitorCompany,licenseNos,startTime,endTime,permitDeviceIds,respondentId,addressId,visitCause,remarks,sendSms');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name: 取消访客预约
     * @param array $param
     * @return array 
     */
    public function appointment_cancel(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/appointment/cancel?accessToken=' . $access_token;
        $data = eyc_array_key($param, 'appointmentId');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name: 创建访客地址
     * @param {array} $param
     * @return array
     */
    public function address_create(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/address/create?accessToken=' . $access_token;
        $data = eyc_array_key($param, 'province|province_code,city|city_code,region|region_code,detailAddress|detail,longitude,latitude');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name:  更新访客地址
     * @param {array} $param
     * @return array
     */
    public function address_update(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/address/update?accessToken=' . $access_token;
        $data = eyc_array_key($param, 'addressId|modian_address_id,province|province_code,city|city_code,region|region_code,detailAddress,longitude,latitude');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name:  删除访客地址
     * @param {array} $param
     * @return array
     */
    public function address_delete(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/address/delete?accessToken=' . $access_token;
        $data = eyc_array_key($param, 'addressId|modian_address_id');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name:  查询全部访客地址
     * @param {array} $param
     * @return array
     */
    public function address_listAll(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/address/listAll?accessToken=' . $access_token;
        return $this->GuzzleHttp->get($url);
    }
    /**
     * @author: 布尔
     * @name:  访客录入人脸
     * @param {array} $param
     * @return array
     */
    public function face_upload(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/face/upload?accessToken=' . $access_token;
        $data = eyc_array_key($param, 'appointmentId,face');
        $r = $this->GuzzleHttp->post($url, $data, [], 'file');
        return $r;
    }
    /**
     * @author: 布尔
     * @name:  访客录入人脸通知
     * @param {array} $param
     * @return array
     */
    public function face_complete(array $param) : array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/face/complete?accessToken=' . $access_token;
        $data = eyc_array_key($param, 'appointmentId');
        return $this->GuzzleHttp->post($url, $data);
    }
}