<?php

namespace EPort\EPortWmsClient\OrderSubmit;

use EPort\EPortWmsClient\Application;
use EPort\EPortWmsClient\Base\BaseClient;
use EPort\EPortWmsClient\Base\Exceptions\ClientError;

/**
 * 订单导入API客户端.
 */
class Client
{
    /**
     * @var Application
     */
    protected $credentialValidate;

    public function __construct(Application $app)
    {
        // parent::__construct($app);
        $this->credentialValidate = $app['credential'];
    }

    /**
     * ************************************   以下为旧示例
     * 提交订单.
     *
     * @throws ClientError
     */
    public function submitOrder(array $infos)
    {
        //使用Credential验证参数
        $this->credentialValidate->setRule(
            [
                'data'      => 'require',
                'timestamp' => 'require',
                'sign'      => 'require',
            ]
        );
        //验证平台代码和电商代码
        if (!$this->credentialValidate->check($infos)) {
            throw new ClientError('主体配置' . $this->credentialValidate->getError());
        }

        $data = $infos['data'];

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
        var_dump($this->credentialValidate->check($data));die();
        if (!$this->credentialValidate->check($data)) {
            throw new ClientError('订单信息' . $this->credentialValidate->getError());
        }

        $this->credentialValidate->setRule(
            [
                'SKU'               => 'require|max:30',
                'sellUnitPrice'     => 'require|max:23|float',
                'sellQty'           => 'require|int',
            ]
        );

        foreach ($data['item'] as $key => $value) {
            //验证订单表商品
            if (!$this->credentialValidate->check($value)) {
                throw new ClientError('订单表体配置' . $this->credentialValidate->getError());
            }

            $this->checkOrderInfo($data['item'], $data);
        }

        // $this->setParams($infos);

        // return $this->httpPostJson('/bds/order');
    }

    /**
     * 定义验证器来校验订单信息.
     */
    public function checkOrderInfo($body, $head)
    {
        // if (array_sum([$head['Ordergoodtotal'], $head['Freight'], $head['Discount'], $head['Tax']]) != $head['ActuralPaid']) {
        //     throw new ClientError('订单表头数据：实际支付金额与订单记录不符');
        // }

        // $price_sum = 0;
        // foreach ($body as $k => $v) {
        //     $price_sum = $price_sum + $v['sellUnitPrice'] * $v['sellQty'];
        // }

        // if ($price_sum != $head['Ordergoodtotal']) {
        //     throw new ClientError('订单表体数据：商品价格之和与订单表体的商品价格不符');
        // }
    }
}
