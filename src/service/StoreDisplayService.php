<?php

namespace EPort\EPortWmsService;

use EPort\EPortWmsClient\Application;
use EPort\EPortWmsClient\Base\Exceptions\ClientError;

/**
 * 保税门店展示.
 */
class StoreDisplayService
{
    /**
     * @var StoreDisplayClient
     */
    private $_bbcDisplayClient;

    public function __construct(Application $app)
    {
        $this->_bbcDisplayClient = $app['bbc_display'];
    }

    /**
     * 门店保税展示现场速递进货申请单接口.
     * @throws ClientError
     * @throws \Exception
     */
    public function purchaseApply(array $infos)
    {
        if (empty($infos)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_bbcDisplayClient->purchaseApply($infos);
    }

    /**
     * 门店保税展示现场速递货品返区申请单接口.
     *
     * @throws ClientError
     * @throws \Exception
     */
    public function returnApply(array $infos)
    {
        if (empty($infos)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_bbcDisplayClient->returnApply($infos);
    }

    /**
     * 门店保税展示现场速递货品转移申请单接口.
     *
     * @throws ClientError
     * @throws \Exception
     */
    public function goodsTransfer(array $infos)
    {
        if (empty($infos)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_bbcDisplayClient->goodsTransfer($infos);
    }

    /**
     * 门店保税展示现场速递订单接口.
     *
     * @throws ClientError
     * @throws \Exception
     */
    public function orderApply(array $infos)
    {
        if (empty($infos)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_bbcDisplayClient->orderApply($infos);
    }
}
