<?php

namespace EPort\EPortWmsClient\StoreDisplay;

use EPort\EPortWmsClient\Application;
use EPort\EPortWmsClient\Base\BaseClient;
use EPort\EPortWmsClient\Base\Exceptions\ClientError;

/**
 * 门店保税展示API客户端.
 */
class Client extends BaseClient
{
    /**
     * @var Application
     */
    protected $credentialValidate;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->credentialValidate = $app['credential'];
    }

    /**
     * 门店保税展示现场速递进货申请单接口.
     * @throws ClientError
     */
    public function purchaseApply(array $data)
    {
        $this->credentialValidate->setRule(
            [
                'shopId'            => 'require|max:22',
                'sinGoodsDocSn'     => 'require|max:22',
                'consigneeName'     => 'require|max:60',
                'consigneeMob'      => 'require|max:20',
                'consigneeProvince' => 'require|max:64',
                'consigneeCity'     => 'require|max:64',
                'consigneeDistrict' => 'require|max:64',
                'consigneeAddress'  => 'require|max:200',
                'billTime'          => 'require|max:32',
                'item'              => 'require|array',
            ]
        );

        if (!$this->credentialValidate->check($data)) {
            throw new ClientError('订单信息' . $this->credentialValidate->getError());
        }

        $this->credentialValidate->setRule(
            [
                'sku'        => 'require|max:30',
                'goodsPrice' => 'require|number',
                'goodsNum'   => 'require|int',
            ]
        );

        foreach ($data['item'] as $key => $value) {
            //验证订单表商品
            if (!$this->credentialValidate->check($value)) {
                throw new ClientError('订单表体配置' . $this->credentialValidate->getError());
            }
        }

        $data['merchId'] = $this->app['config']->get('merchId');

        $this->setUri('shop/bdd/cb/ingoods');

        $result = $this->postData($data);

        if (0 != $result['code']) {
            throw new \Exception('接口业务异常回应:' . $result['msg'] . ' 错误码: ' . $result['code']);
        }

        return $result['data'];
    }

    /**
     * 门店保税展示现场速递货品返区申请单接口.
     *
     * @throws ClientError
     */
    public function returnApply(array $data)
    {
        $this->credentialValidate->setRule(
            [
                'shopId'           => 'require|max:22',
                'merchReturnDocSn' => 'require|max:22',
                'grossWeight'      => 'require',
                'billTime'         => 'require|max:32',
                'item'             => 'require|array',
            ]
        );

        if (!$this->credentialValidate->check($data)) {
            throw new ClientError('订单信息' . $this->credentialValidate->getError());
        }

        $this->credentialValidate->setRule(
            [
                'sku'      => 'require|max:30',
                'goodsNum' => 'require|int',
                'ctnNo'    => 'require|max:24',
            ]
        );

        foreach ($data['item'] as $key => $value) {
            //验证订单表商品
            if (!$this->credentialValidate->check($value)) {
                throw new ClientError('订单表体配置' . $this->credentialValidate->getError());
            }
        }

        $data['merchId'] = $this->app['config']->get('merchId');

        $this->setUri('shop/bdd/cb/returngoods');

        $result = $this->postData($data);

        if (0 != $result['code']) {
            throw new \Exception('接口业务异常回应:' . $result['msg'] . ' 错误码: ' . $result['code']);
        }

        return $result['data'];
    }

    /**
     * 门店保税展示现场速递货品转移申请单接口.
     * @throws ClientError
     */
    public function goodsTransfer(array $data)
    {
        $this->credentialValidate->setRule(
            [
                'merchTransDocSn' => 'require|max:22',
                'rollOutShopId'   => 'require|max:22',
                'rollInShopId'    => 'require|max:22',
                'billTime'        => 'require|max:32',
                'item'            => 'require|array',
            ]
        );

        if (!$this->credentialValidate->check($data)) {
            throw new ClientError('订单信息' . $this->credentialValidate->getError());
        }

        $this->credentialValidate->setRule(
            [
                'sku'      => 'require|max:30',
                'goodsNum' => 'require|int',
            ]
        );

        foreach ($data['item'] as $key => $value) {
            //验证订单表商品
            if (!$this->credentialValidate->check($value)) {
                throw new ClientError('订单表体配置' . $this->credentialValidate->getError());
            }
        }

        $data['merchId'] = $this->app['config']->get('merchId');

        $this->setUri('shop/bdd/cb/transgoods');

        $result = $this->postData($data);

        if (0 != $result['code']) {
            throw new \Exception('接口业务异常回应:' . $result['msg'] . ' 错误码: ' . $result['code']);
        }

        return $result['data'];
    }

    /**
     * 门店保税展示现场速递订单接口.
     * @throws ClientError
     */
    public function orderApply(array $data)
    {
        $this->credentialValidate->setRule(
            [
                'shopId'            => 'require|max:22',
                'merchOrderId'      => 'require|max:32',
                'buyerIdType'       => 'require|in:1,2',
                'buyerIdCode'       => 'require|max:60',
                'buyerName'         => 'require|max:60',
                'buyerTel'          => 'require|max:30',
                'payerIdType'       => 'require|in:1,2',
                'payerIdCode'       => 'require|max:60',
                'payerName'         => 'require|max:60',
                'payerMob'          => 'require|max:30',
                'consigneeProvince' => 'require|max:64',
                'consigneeCity'     => 'require|max:64',
                'consigneeDistrict' => 'require|max:64',
                'consigneeAddress'  => 'require|max:200',
                'acturalPaid'       => 'require|number',
                'payTime'           => 'require|max:32',
                'exprAgreementType' => 'require|max:2',
                'buyerBillTime'     => 'require|max:32',
                'declExprFee'       => 'require|number',
                'declPostTax'       => 'require|number',
                'remark'            => 'require|max:100',
                'item'              => 'require|array',
            ]
        );

        if (!$this->credentialValidate->check($data)) {
            throw new ClientError('订单信息' . $this->credentialValidate->getError());
        }

        $this->credentialValidate->setRule(
            [
                'sku'           => 'require|max:30',
                'sellUnitPrice' => 'require|number',
                'sellQty'       => 'require|int',
            ]
        );

        foreach ($data['item'] as $key => $value) {
            //验证订单表商品
            if (!$this->credentialValidate->check($value)) {
                throw new ClientError('订单表体配置' . $this->credentialValidate->getError());
            }
        }

        $this->checkorderApply($data['item'], $data);

        $data['merchId'] = $this->app['config']->get('merchId');

        $this->setUri('shop/bdd/cb/order');

        $result = $this->postData($data);

        if (0 != $result['code']) {
            throw new \Exception('接口业务异常回应:' . $result['msg'] . ' 错误码: ' . $result['code']);
        }

        return $result['data'];
    }

    /**
     * 定义验证器来校验现场速递订单信息.
     */
    public function checkorderApply($body, $head)
    {
        $price_sum = 0;
        foreach ($body as $k => $v) {
            $price_sum = $price_sum + $v['sellUnitPrice'] * $v['sellQty'];
        }

        if ($price_sum != $head['acturalPaid']) {
            throw new ClientError('订单表体数据：商品价格之和与订单表体的商品价格不符');
        }
    }
}
