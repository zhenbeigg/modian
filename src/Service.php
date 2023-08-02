<?php

/*
 * @author: 布尔
 * @name: 魔点service服务
 * @desc: 介绍
 * @LastEditTime: 2023-08-02 10:02:32
 */
namespace Eykj\Modian;

use Eykj\Base\GuzzleHttp;
use App\Model\ModianAuth;
use function Hyperf\Support\env;

class Service
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?ModianAuth $ModianAuth;

    protected $url = 'https://device.qixuw.com';

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp, ?ModianAuth $ModianAuth)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->ModianAuth = $ModianAuth;
    }
    /**
     * @author: 布尔
     * @name: 获取access_token
     * @param array $param
     * @return string $app_token appToken
     */
    public function get_app_token(array $param): string
    {
        /* 检测是正式环境还是测试环境 */
        if(env('SERVE_ENV','') != 'release'){
            $r = $this->GuzzleHttp->get($this->url . '/modian/get_app_token');
            return $r['result'];
        }
        $key = 'modian_app_token';
        if (!redis()->get($key)) {
            /* 获取配置参数 */
            $modian_url = env('MODIAN_URL', '');
            $modian_appid = env('MODIAN_APPID', '');
            $modian_appkey = env('MODIAN_APPKEY', '');
            $url = $modian_url . '/app/getAppToken?appId=' . $modian_appid . '&appKey=' . $modian_appkey;
            $r = $this->GuzzleHttp->get($url);
            if ($r["result"] == 0) {
                $app_token = $r["data"]["appToken"];
                redis()->set($key, $app_token, $r["data"]["expires"]);
            } else {
                return '';
            }
        } else {
            $app_token = redis()->get($key);
        }
        return $app_token;
    }

    /**
     * @author: 布尔
     * @name: 机构授权
     * @param array $param
     * @return string $orgId 机构 ID
     */
    public function post_auth_org_by_extra(array $param)
    {
        /* 获取appToken */
        $app_token = $this->get_app_token($param);
        /* 获取配置参数 */
        $modian_url = env('MODIAN_URL', '');
        $url = $modian_url . '/app/authOrgByExtra?appToken=' . $app_token;
        $data['corpId'] = $param['corpid'];
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            return $r;
        }
        return $r["data"];
    }

    /**
     * @author: 布尔
     * @name: 获取accessToken
     * @param array $param
     * @return string
     */
    public  function get_access_token(array $param): string
    {
        $key = $param['corpid'] . '_modian_access_token';
        if (!redis()->get($key)) {
            /* 获取appToken */
            $app_token = $this->get_app_token($param);
            /* 获取配置参数 */
            $modian_url = env('MODIAN_URL', '');
            /* 获取魔点授权企业信息 */
            $filter = eyc_array_key($param, 'corpid');
            $info = $this->ModianAuth->get_info($filter);
            $url = $modian_url . '/app/getOrgAccessToken?appToken=' . $app_token . '&orgAuthKey=' . $info["org_auth_key"] . '&orgId=' . $info["org_id"];
            $r = $this->GuzzleHttp->get($url);
            if ($r["result"] == 0) {
                $access_token = $r["data"]["accessToken"];
                redis()->set($key, $access_token, $r["data"]["expires"]);
            } else {
                logger()->error('获取魔点accessToken', $r);
                return false;
            }
        } else {
            $access_token = redis()->get($key);
        }
        return $access_token;
    }


    /**
     * @author: 布尔
     * @name: 获取企业accessToken
     * @param array $param
     * @return string
     */
    public  function get_org_access_token(array $param): string
    {
        $key = $param['org_id'] . '_modian_org_access_token';
        if (!redis()->get($key)) {
            /* 获取配置参数 */
            $modian_url = env('MODIAN_URL', '');
            $url = $modian_url . '/org/getOrgAccessToken?orgAuthKey=' . $param['org_auth_key'] . '&orgId=' . $param['org_id'];
            $r = $this->GuzzleHttp->get($url);
            if ($r["result"] == 0) {
                $access_token = $r["data"]["accessToken"];
                redis()->set($key, $access_token, $r["data"]["expires"]);
            } else {
                logger()->error('获取魔点orgaccessToken', $r);
                return false;
            }
        } else {
            $access_token = redis()->get($key);
        }
        return $access_token;
    }

    /**
     * @author: 布尔
     * @name:  授权验签
     * @param array $param
     * @return bool $r 验证结果
     */
    public function org_check_sign(array $param): bool
    {
        $bodyMd5 = MD5(json_encode($param['body'], 320));
        $str = $param['nonce'] . $param['signVersion'] . $param['timestamp'] . $bodyMd5;
        $appKey = env('MODIAN_APPKEY');
        $sign = base64_encode(hash_hmac("sha1", $str, $appKey, true));
        if ($sign == $param['sign']) {
            return true;
        }
        return false;
    }

    /**
     * @author: 布尔
     * @name:  三方鉴权验签
     * @param array $param
     * @return bool $r 验证结果
     */
    public function recognize_check_sign(array $param): bool
    {
        $bodyMd5 = MD5(json_encode($param['body'], 320));
        $str = $param['nonce'] . $param['org_id'] . $param['deviceSn'] . $param['signVersion'] . $param['timestamp'] . $bodyMd5;
        $sign = base64_encode(hash_hmac("sha1", $str, $param['device_secret_key'], true));
        if ($sign == $param['sign']) {
            return true;
        }
        return false;
    }

    /**
     * @author: 布尔
     * @name:  开门验签
     * @param array $param
     * @return string $r 验证结果
     */
    public function rec_check_sign(array $param): string
    {
        $bodyMd5 = MD5(json_encode($param['body'], 320));
        $str = $param['nonce'] . $param['org_id'] . $param['signVersion'] . $param['timestamp'] . $bodyMd5;
        $sign = base64_encode(hash_hmac("sha1", $str, $param['org_auth_key'], true));
        if ($sign == $param['signature']) {
            return true;
        }
        return false;
    }
}