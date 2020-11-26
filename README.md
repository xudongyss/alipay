# 支付宝SDK

支付宝新版SDK [Alipay Easy SDK](https://github.com/alipay/alipay-easysdk) 未涉及到的接口

[TOC]

## 安装

```
composer require xudongyss/alipay
```

## 快速使用
### 初始化

```php
require_once 'vendor/autoload.php';

use XuDongYss\AliPay\Pay;

$appId = '';
/* 应用私钥 */
$merchantPrivateKey = '';
/* 支付宝公钥 */
$alipayPublicKey = '';

Pay::init($appId, $merchantPrivateKey, $alipayPublicKey);
```

### 单笔转账

```php
/* 商户端的唯一订单号, 对于同一笔转账请求，商户需保证该订单号唯一 */
$out_biz_no = '';
/* 订单总金额，单位为元m 精确到小数点后两位, 产品取值范围[0.1,100000000] */
$trans_amount = 1;
/* 收款方唯一标识 */
$identity = '';
/* 收款方真实姓名 */
$name = '';
/* 可选：转账业务的标题, 用于在支付宝用户的账单里显示 */
$order_title = '';
/* 可选：收款方标识类型, ALIPAY_LOGON_ID 支付宝登录号，支持邮箱和手机号格式(默认值), ALIPAY_USER_ID 支付宝的会员ID，默认值：ALIPAY_LOGON_ID */
$identity_type = '';
Pay::fundTransUniTransfer($out_biz_no, $trans_amount, $identity, $name, $order_title, $identity_type);
```