<?php

namespace EPort\EPortWmsClient\OrderSubmit;

use EPort\EPortWmsClient\Application;
use EPort\EPortWmsClient\Base\BaseClient;
use EPort\EPortWmsClient\Base\Exceptions\ClientError;

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
    public function submitOrder(array $data)
    {
        $this->credentialValidate->setRule(
            [
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
                'cusOrderSucceeded' => 'require|max:1|in:0,1',
                'paySucceeded'      => 'require|max:1|in:0',
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
        }

        $this->checkOrderInfo($data['item'], $data);

        $data['merchId'] = $this->app['config']->get('merchId');

        $this->setUri('bds/order');

        $result = $this->postData($data);

        if (0 != $result['code']) {
            throw new \Exception('接口业务异常回应:' . $result['msg'] . ' 错误码: ' . $result['code']);
        }

        return $result['data'];
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
