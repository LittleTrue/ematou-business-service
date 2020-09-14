<?php

namespace EPort\EPortWmsClient\StoreDisplay;

use EPort\EPortWmsClient\Application;
use EPort\EPortWmsClient\Base\BaseClient;
use EPort\EPortWmsClient\Base\MD5;

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
    public function purchaseApply(array $data, $secret_key = '')
    {
        $this->credentialValidate->setRule(
            [
                'shopId'            => 'require|max:22',
                'merchId'           => 'require|max:22',
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

        ksort($data);
        $send_data = [
            'data'      => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'timestamp' => time(),
            'merchId'   => $data['merchId'],
        ];

        //获取签名
        $md5_sign          = new MD5();
        $sign_string       = $md5_sign->MD5Sign($send_data, $secret_key);
        $send_data['sign'] = $sign_string;
        var_dump($send_data);

        $this->setParams($send_data);

        var_dump($this->httpPostJson('shop/bdd/cb/ingoods'));
    }

    /**
     * 门店保税展示现场速递货品返区申请单接口.
     *
     * @throws ClientError
     */
    public function returnApply(array $data, $secret_key = '')
    {
        $this->credentialValidate->setRule(
            [
                'shopId'           => 'require|max:22',
                'merchId'          => 'require|max:22',
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

        ksort($data);
        $send_data = [
            'data'      => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'timestamp' => time(),
            'merchId'   => $data['merchId'],
        ];

        //获取签名
        $md5_sign          = new MD5();
        $sign_string       = $md5_sign->MD5Sign($send_data, $secret_key);
        $send_data['sign'] = $sign_string;
        var_dump($send_data);

        $this->setParams($send_data);

        var_dump($this->httpPostJson('shop/bdd/cb/returngoods'));
    }

    /**
     * 门店保税展示现场速递货品转移申请单接口.
     * @throws ClientError
     */
    public function goodsTransfer(array $data, $secret_key = '')
    {
        $this->credentialValidate->setRule(
            [
                'merchId'         => 'require|max:22',
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

        ksort($data);
        $send_data = [
            'data'      => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'timestamp' => time(),
            'merchId'   => $data['merchId'],
        ];

        //获取签名
        $md5_sign          = new MD5();
        $sign_string       = $md5_sign->MD5Sign($send_data, $secret_key);
        $send_data['sign'] = $sign_string;
        var_dump($send_data);

        $this->setParams($send_data);

        var_dump($this->httpPostJson('shop/bdd/cb/transgoods'));
    }

    /**
     * 门店保税展示现场速递订单接口.
     * @throws ClientError
     */
    public function orderApply(array $data, $secret_key = '')
    {
        $this->credentialValidate->setRule(
            [
                'shopId'            => 'require|max:22',
                'merchId'           => 'require|max:22',
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

        ksort($data);
        $send_data = [
            'data'      => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'timestamp' => time(),
            'merchId'   => $data['merchId'],
        ];

        //获取签名
        $md5_sign          = new MD5();
        $sign_string       = $md5_sign->MD5Sign($send_data, $secret_key);
        $send_data['sign'] = $sign_string;
        var_dump($send_data);

        $this->setParams($send_data);

        var_dump($this->httpPostJson('shop/bdd/cb/order'));
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
