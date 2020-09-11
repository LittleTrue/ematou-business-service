<?php

require_once __DIR__ . '/vendor/autoload.php';

use EPort\EPortWmsClient\Application;
use EPort\EPortWmsService\BBCOrderService;

$ioc_con_app = new Application([
    'BaseUri'  => 'http://120.55.54.0:8003/api/',
    'Account'  => 'ewms',
    'Password' => '888888',
]);

//订单导入服务-----
$bankSrv = new BBCOrderService($ioc_con_app);

$array = [
    'data' => [
        'merchId'           => '123',
        'merchOrderId'      => '202009101518',
        'buyerIdType'       => '1',
        'buyerIdCode'       => '440583199705234511',
        'buyerName'         => '陈子安',
        'buyerTel'          => '15013000000',
        'payerIdType'       => '1',
        'payerIdCode'       => '',
        'payerName'         => '陈子安',
        'payerMob'          => '15013000000',
        'consigneeIdType'   => '1',
        'consigneeIdCode'   => '440583199705234511',
        'consigneeName'     => '陈子安',
        'consigneeMob'      => '15013000000',
        'consigneeTel'      => '15013000000',
        'consigneeProvince' => '广东省',
        'consigneeCity'     => '汕头市',
        'consigneeDistrict' => '金平区',
        'consigneeAddress'  => '1号',
        'acturalPaid'       => '199',
        'payTime'           => '2020-09-10 15:20:10',
        'exprAgreementType' => '00',
        'exprType'          => '00',
        'exprCompId'        => 'yto',
        'buyerBillTime'     => '2020-09-10 15:20:10',
        'declExprFee'       => '0',
        'declPostTax'       => '0',
        'item'              => [
            [
                'sku'           => 'SKU000001',
                'sellUnitPrice' => '199',
                'sellQty'       => '1'
            ]
        ],
    ],
    'sign'  => '',
    'timestamp' => time(),
];

// try {
    print_r(json_encode($bankSrv->submitOrder($array)));
// } catch (\Exception $e) {
//     print_r(json_encode($e->getMessage()));
// }

/**
 * 签名.
 * @param param [签名对象]
 */
function sign($param)
{
    $string = $this->secret_key . 'data' . trim($param['data'], '"') . 'merchId' . $param['merchId'] . 'timestamp' . $param['timestamp'];
    return strtolower(md5($string));
}

