<?php

namespace EPort\EPortWmsService;

use EPort\EPortWmsClient\Application;
use EPort\EPortWmsClient\Base\Exceptions\ClientError;

/**
 * 保税备货订单服务.
 */
class BBCOrderService
{
    /**
     * @var BBCOrderClient
     */
    private $_bbcOrderClient;

    public function __construct(Application $app)
    {
        $this->_bbcOrderClient = $app['bbc_order'];
    }

    /**
     * 提交订单.
     *
     * @throws ClientError
     * @throws \Exception
     */
    public function submitOrder(array $infos, $key = '')
    {
        if (empty($infos)) {
            throw new ClientError('参数缺失', 1000001);
        }

        if (empty($key)) {
            throw new ClientError('密钥缺失', 1000001);
        }

        return $this->_bbcOrderClient->submitOrder($infos, $key);
    }
}
