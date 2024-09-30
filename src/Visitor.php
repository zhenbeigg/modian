<?php

/*
 * @author: 布尔
 * @name: 访客
 * @desc: 介绍
 * @LastEditTime: 2024-09-30 18:22:17
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
    public function appointment_create(array $param): array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/appointment/create?accessToken=' . $access_token;
        $data = eyc_array_key($param, 'visitorName|name,visitorMobile|mobile,visitorCompany,licenseNos,startTime,endTime,permitDeviceIds,respondentId|userid,addressId|modian_address_id,visitCause|purpose,remarks,sendSms');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name: 取消访客预约
     * @param array $param
     * @return array 
     */
    public function appointment_cancel(array $param): array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/appointment/cancel?accessToken=' . $access_token;
        $data = eyc_array_key($param, 'appointmentId|modian_appointment_id');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name: 创建访客地址
     * @param {array} $param
     * @return array
     */
    public function address_create(array $param): array
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
    public function address_update(array $param): array
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
    public function address_delete(array $param): array
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
    public function address_listAll(array $param): array
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
    public function face_upload(array $param): array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/face/upload?accessToken=' . $access_token;
        $arr['name'] = 'appointmentId';
        $arr['contents'] = $param['appointmentId'];
        $data[] = $arr;
        $arr1['name'] = 'face';
        $arr1['contents'] = fopen($param['face'], 'r+');
        $data[] = $arr1;
        $r = $this->GuzzleHttp->post($url, $data, [], 'file');
        if (!isset($param['face_url'])) {
            if (is_resource($param['face'])) {
                fclose($param['face']);
            }
        }
        return $r;
    }
    /**
     * @author: 布尔
     * @name:  访客录入人脸通知
     * @param {array} $param
     * @return array
     */
    public function face_complete(array $param): array
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/face/complete?accessToken=' . $access_token;
        $data = eyc_array_key($param, 'appointmentId');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * 获取访客详情
     */
    public function get_record_info(array $param)
    {
        /* 查询魔点access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/visitor/getRecordInfo?accessToken=' . $access_token . "&appointmentId=" . $param["appointmentId"];
        return $this->GuzzleHttp->get($url);
    }
}
