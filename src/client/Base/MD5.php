<?php

namespace EPort\EPortWmsClient\Base;

/**
 * Class Config.
 */
class MD5
{
    //进行MD5加签
    public function MD5Sign($param, $key)
    {
        $string = $key . 'data' . trim($param['data'], '"') . 'merchId' . $param['merchId'] . 'timestamp' . $param['timestamp'];
        var_dump($string);
        // die();
        return strtolower(md5($string));
    }
}
