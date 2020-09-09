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
                'Datas'       => 'require',
                'PlateCode'   => 'require',
                'EntreCordNo' => 'require',
            ]
        );
        //验证平台代码和电商代码
        if (!$this->credentialValidate->check($infos)) {
            throw new ClientError('主体配置' . $this->credentialValidate->getError());
        }

        $data = $infos['Datas'];

        $this->credentialValidate->setRule(
            [
                'OrderNo'             => 'require|max:60',
                'Waybillno'           => 'max:60',
                'EnterpriseCode'      => 'require|max:10',
                'Ordername'           => 'require|max:30',
                'BuyerRegno'          => 'require|max:60',
                'Orderdocid'          => 'require|max:30',
                'Orderphone'          => 'require|max:50',
                'Ordergoodtotal'      => 'require|max:13|float',
                'Freight'             => 'require|max:13|float',
                'Discount'            => 'require|max:13|float',
                'Tax'                 => 'require|max:13|float',
                'ActuralPaid'         => 'require|max:13|float',
                'ReceivingUserName'   => 'require|max:100',
                'ReceivingUserMobile' => 'require|max:50',
                'ReceivingUserAddr'   => 'require|max:255',
                'ConsigneeProvince'   => 'require|max:100',
                'ConsigneeCity'       => 'require|max:100',
                'ConsigneeArea'       => 'require|max:100',
                'Note'                => 'max:255',
                'BillTemplate'        => 'require|max:50',
            ]
        );
        //验证订单表头配置
        foreach ($data as $k => $v) {
            if (!$this->credentialValidate->check($v['Head'])) {
                throw new ClientError('订单表头配置' . $this->credentialValidate->getError());
            }
        }

        $this->credentialValidate->setRule(
            [
                'OrderNo'      => 'require|max:60',
                'Copgno'       => 'require|max:30',
                'Decprice'     => 'require|max:13|float',
                'Gqty'         => 'require|max:13|float',
                'TradeCountry' => 'max:60',
                'Notes'        => 'max:255',
            ]
        );

        foreach ($data as $key => $value) {
            //验证订单表体配置
            foreach ($value['Body'] as $k => $v) {
                if (!$this->credentialValidate->check($v)) {
                    throw new ClientError('订单表体配置' . $this->credentialValidate->getError());
                }
            }

            $this->checkOrderInfo($value['Body'], $value['Head']);
        }

        $this->setParams($infos);

        return $this->httpPostJson('/OrderManage/ImportData');
    }

    /**
     * 定义验证器来校验订单信息.
     */
    public function checkOrderInfo($body, $head)
    {
        if (array_sum([$head['Ordergoodtotal'], $head['Freight'], $head['Discount'], $head['Tax']]) != $head['ActuralPaid']) {
            throw new ClientError('订单表头数据：实际支付金额与订单记录不符');
        }

        $price_sum = 0;
        foreach ($body as $k => $v) {
            if ($v['OrderNo'] != $head['OrderNo']) {
                throw new ClientError('订单表体数据：表体电子订单编号与表头的不符');
            }

            if ($v['OrderNo'] != $head['OrderNo']) {
                throw new ClientError('订单表体数据：表体电子订单编号与表头的不符');
            }

            $price_sum = $price_sum + $v['Decprice'] * $v['Gqty'];
        }

        if ($price_sum != $head['Ordergoodtotal']) {
            throw new ClientError('订单表体数据：商品价格之和与订单表体的商品价格不符');
        }
    }
}
