<?php

namespace EPort\EPortWmsClient\OrderSubmit;

use EPort\EPortWmsClient\Application;
use EPort\EPortWmsClient\Base\BaseClient;
use EPort\EPortWmsClient\Base\Exceptions\ClientError;
use EPort\EPortWmsClient\Base\MD5;

/**
 * 订单导入API客户端.
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
     * 提交订单.
     *
     * @throws ClientError
     */
    public function submitOrder(array $data, $secret_key = '')
    {
        $this->credentialValidate->setRule(
            [
                'merchId'           => 'require|max:22',
                'merchOrderId'      => 'max:32',
                'buyerIdType'       => 'require|max:1|in:1,2',
                'buyerIdCode'       => 'require|max:60',
                'buyerName'         => 'require|max:60',
                'buyerTel'          => 'require|max:30',
                'payerIdType'       => 'require|max:1|in:1,2',
                'payerIdCode'       => 'require|max:60',
                'payerName'         => 'require|max:60',
                'payerMob'          => 'require|max:30',
                'consigneeIdType'   => 'require|max:1|in:1,2',
                'consigneeIdCode'   => 'require|max:60',
                'consigneeName'     => 'require|max:60',
                'consigneeMob'      => 'requireif:consigneeTel,|max:20',
                'consigneeTel'      => 'requireif:consigneeMob,|max:20',
                'consigneeProvince' => 'require|max:64',
                'consigneeCity'     => 'require|max:64',
                'consigneeDistrict' => 'require|max:64',
                'consigneeAddress'  => 'require|max:200',
                'acturalPaid'       => 'require|max:20|float',
                'payTime'           => 'require|max:32',
                'exprAgreementType' => 'require|max:2|in:00,01,02',
                'exprType'          => 'require|max:2',
                'exprCompId'        => 'require|max:10',
                'buyerBillTime'     => 'require|max:32',
                'declExprFee'       => 'require|max:20|float',
                'declPostTax'       => 'require|max:20|float',
                'item'              => 'require|array',
            ]
        );

        if (!$this->credentialValidate->check($data)) {
            throw new ClientError('订单信息' . $this->credentialValidate->getError());
        }

        $this->credentialValidate->setRule(
            [
                'SKU'           => 'require|max:30',
                'sellUnitPrice' => 'require|max:23|float',
                'sellQty'       => 'require|int',
            ]
        );

        foreach ($data['item'] as $key => $value) {
            //验证订单表商品
            if (!$this->credentialValidate->check($value)) {
                throw new ClientError('订单表体配置' . $this->credentialValidate->getError());
            }

            $this->checkOrderInfo($data['item'], $data);
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

        var_dump($this->httpPostJson('bds/order'));
    }

    /**
     * 定义验证器来校验订单信息.
     */
    public function checkOrderInfo($body, $head)
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
