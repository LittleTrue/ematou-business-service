<?php

require_once __DIR__ . '/vendor/autoload.php';

use EPort\EPortWmsClient\Application;
use EPort\EPortWmsService\BBCOrderService;
use EPort\EPortWmsService\StoreDisplayService;

$ioc_con_app = new Application([
    'BaseUri'   => 'http://wstest.ds-bay.com/al/',
    'secretKey' => 'hzxCjtGlY9d5AJj9',
    'merchId'   => 'mhbs996643550862512129',
]);

//-----------------------------------------------------------------------------------
//订单导入服务-----
//-----------------------------------------------------------------------------------
$bankSrv = new BBCOrderService($ioc_con_app);

$data = [
    'merchOrderId'      => '202009101518',
    'buyerIdType'       => '1',
    'buyerIdCode'       => '440183199809293733',
    'buyerName'         => '陈子安',
    'buyerTel'          => '13022076770',
    'payerIdType'       => '1',
    'payerIdCode'       => '440183199809293733',
    'payerName'         => '陈子安',
    'payerMob'          => '13022076770',
    'consigneeIdType'   => '1',
    'consigneeIdCode'   => '440583199705234511',
    'consigneeName'     => '陈子安',
    'consigneeMob'      => '13022076770',
    'consigneeTel'      => '',
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
    'cusOrderSucceeded' => '0',
    'paySucceeded'      => '0',
    'item'              => [
        [
            'sku'           => 'ISSFYX0000132',
            'sellUnitPrice' => '199',
            'sellQty'       => '1',
        ],
    ],
];

// print_r($bankSrv->submitOrder($data));

//-----------------------------------------------------------------------------------
//门店保税展示现场速递进货申请单接口
//-----------------------------------------------------------------------------------
$storeDisplay = new StoreDisplayService($ioc_con_app);

$data = [
    'shopId'                => 'wbsp722125006189940736',
    'merchName'             => '中网科技',
    'sinGoodsDocSn'         => '2018042413364444675',
    'bondedCode'            => '060100',
    'wareCode'              => 'qhbsq',
    'consigneeIdType'       => '1',
    'consigneeIdCode'       => '430611199211085562',
    'consigneeName'         => '赵起胜',
    'consigneeMob'          => '15886451545',
    'consigneeProvinceCode' => '220000',
    'consigneeProvince'     => '吉林省',
    'consigneeCityCode'     => '630200',
    'consigneeCity'         => '吉林市',
    'consigneeDistrictCode' => '340503',
    'consigneeDistrict'     => '龙潭区',
    'consigneeTel'          => '13558390776',
    'consigneeCountryCode'  => '142',
    'consigneeAddress'      => '西丽镇大学城',
    'consigneeZipCode'      => '430424',
    'billTime'              => '2018-03-01 03:49:08',
    'item'                  => [
        [
            'sku'        => 'ISSFYX0000067',
            'goodsPrice' => '7.95',
            'goodsNum'   => '3',
        ],
    ],
];

print_r($storeDisplay->purchaseApply($data));
die();
//-----------------------------------------------------------------------------------
//门店保税展示现场速递货品转移申请单接口
//-----------------------------------------------------------------------------------
$storeDisplay = new StoreDisplayService($ioc_con_app);

$data = [
    'shopId'           => 'wbsp722125006189940736',
    'merchName'        => '中网科技',
    'merchReturnDocSn' => '2019051513364444675',
    'cusCode'          => '06',
    'bondedCode'       => '060100',
    'wareCode'         => 'qhbsq',
    'grossWeight'      => 4.3,
    'billTime'         => '2018-03-01 03:49:08',
    'item'             => [
        [
            'sku'      => 'ISSFYX0000132',
            'goodsNum' => 3,
            'ctnNo'    => '45',
        ],
    ],
];

// print_r($storeDisplay->returnApply($data));

//-----------------------------------------------------------------------------------
//门店保税展示现场速递订单接口
//-----------------------------------------------------------------------------------
$storeDisplay = new StoreDisplayService($ioc_con_app);

$data = [
    'merchName'       => '中网科技',
    'merchTransDocSn' => '2018042413364444675',
    'rollOutShopId'   => 'wbsp722125006189940736',
    'rollInShopId'    => 'wbsp722125006189940736',
    'billTime'        => '2018-03-01 03:49:08',
    'item'            => [
        [
            'sku'      => 'ISSFYX0000133',
            'wareCode' => 'qhbsq',
            'goodsNum' => 3,
        ],
    ],
];
// print_r($storeDisplay->goodsTransfer($data));

//-----------------------------------------------------------------------------------
//门店保税展示现场速递货品返区申请单接口
//-----------------------------------------------------------------------------------
$storeDisplay = new StoreDisplayService($ioc_con_app);

$data = [
    'shopId'                => 'wbsp722125006189940736',
    'merchName'             => null,
    'merchOrderId'          => '20171154112341815465',
    'buyerIdType'           => '1',
    'buyerIdCode'           => '430611199211085562',
    'buyerName'             => '张道岚',
    'buyerTel'              => '15814451534',
    'payerIdType'           => '1',
    'payerIdCode'           => '430611199211085562',
    'payerName'             => '张道岚',
    'payerMob'              => '15814451534',
    'consigneeIdType'       => '1',
    'consigneeIdCode'       => '430611199211085562',
    'consigneeName'         => '张道岚',
    'consigneeMob'          => '13530611111',
    'consigneeTel'          => '',
    'consigneeCountryCode'  => '142',
    'consigneeProvinceCode' => '430000',
    'consigneeProvince'     => '湖南省',
    'consigneeCityCode'     => '630200',
    'consigneeCity'         => '海东市',
    'consigneeDistrictCode' => '340503',
    'consigneeDistrict'     => '花山区',
    'consigneeAddress'      => '南山区文心六路后海天虹一楼第六大道馆',
    'consigneeZipCode'      => '1515',
    'payEntCusCode'         => '4403160Z3Y',
    'payNo'                 => '2017111521001104000249622093',
    'acturalPaid'           => 34.23000,
    'payTime'               => '2017-11-15 03:15:00',
    'exprType'              => '00',
    'exprAgreementType'     => '00',
    'exprCompId'            => 'yunda',
    'buyerBillTime'         => '2018-03-01 03:49:08',
    'cusCode'               => '06',
    'bondedCode'            => '060100',
    'wareCode'              => 'qhbsq',
    'declExprFee'           => 0.00,
    'declPostTax'           => 0.00,
    'extraTag'              => 'ZWSH418736358190878720',
    'remark'                => 'ZS',
    'platSn'                => null,
    'cusPaymentSucceeded'   => '0',
    'cusOrderSucceeded'     => '0',
    'paySucceeded'          => '0',
    'buyerPayerCheck'       => '0',
    'transMode'             => '20',
    'item'                  => [
        [
            'sku'           => 'ISSFYX0000132',
            'sellUnitPrice' => 34.23000,
            'sellQty'       => 1,
        ],
    ],
];
// print_r($storeDisplay->orderApply($data));
