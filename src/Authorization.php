<?php

namespace Lyx\Micro\Tools;

use App\Common\CodeMsg;
use Exception;
use Firebase\JWT\JWT;
use Phalcon\Di;
use Phalcon\Http\Request;
use App\Common\Code;

class Authorization
{
    /**
     * 创建token
     * @param int $id
     * @return array
     */
    public static function createToken(int $id)
    {
        $config = Di::getDefault()->get('config');
        $time = time();
        $expireTime = $time + $config->jwt->expire;
        $data = array(
            'iat' => $time,  //创建时间
            'exp' => $expireTime,//有效时间
            'id' => $id
        );

        $token = JWT::encode($data, $config->jwt->key);
        return compact('expireTime', 'token');
    }

    /**
     * 解析token
     * @return array
     * @throws CustomException
     */
    public static function analyzeToken()
    {
        $request = new Request();
        $auth = explode(' ', $request->getHeader('Authorization'));
        if (count($auth) !== 2 || $auth[0] !== 'Bearer') {
            throw new CustomException(Code::INVALID_TOKEN, CodeMsg::get(Code::INVALID_TOKEN));
        }

        try {
            $jwtKey = Di::getDefault()->get('config')->jwt->key;
            $ret = (array)JWT::decode($auth[1], $jwtKey, array('HS256'));
        } catch (Exception $e) {
            throw new CustomException(Code::FORBIDDEN, $e->getMessage());
        }

        return $ret ?? [];
    }
}