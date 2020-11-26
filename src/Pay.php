<?php
namespace XuDongYss\AliPay;

use XuDongYss\AliPay\Request\AlipayFundTransUniTransferRequest
use XuDongYss\AliPay\Request\AlipayTradePagePayRequest

class Pay extends Base{
	/**
     * 单笔转账
     * @param string    $out_biz_no     商户端的唯一订单号, 对于同一笔转账请求，商户需保证该订单号唯一
     * @param float     $trans_amount   订单总金额，单位为元m 精确到小数点后两位, TRANS_ACCOUNT_NO_PWD产品取值范围[0.1,100000000]
     * @param string    $identity       收款方唯一标识
     * @param string    $name           收款方真实姓名
     * @param string    $order_title    转账业务的标题, 用于在支付宝用户的账单里显示
     * @param string    $identity_type  收款方标识类型, ALIPAY_LOGON_ID 支付宝登录号，支持邮箱和手机号格式(默认值), ALIPAY_USER_ID 支付宝的会员ID
     * 其他参数解释
     * product_code: 业务产品码
     *     TRANS_ACCOUNT_NO_PWD: 单笔无密转账到支付宝账户, $trans_amount 取值范围: [0.1,100000000]
     *     TRANS_BANKCARD_NO_PWD: 单笔无密转账到银行卡
     *     STD_RED_PACKET: 收发现金红包, $trans_amount 取值范围: [0.01,100000000]
     */
    public static function fundTransUniTransfer($out_biz_no, $trans_amount, $identity, $name, $order_title = '', $identity_type = 'ALIPAY_LOGON_ID') {
        /* 基础信息 */
        $bizContent = [
            'out_biz_no'=> $out_biz_no,
            'trans_amount'=> $trans_amount,
            'product_code'=> 'TRANS_ACCOUNT_NO_PWD',
        ];
        
        if($order_title) $bizContent['order_title'] = $order_title;
        /* 收款方信息 */
        $bizContent['payee_info'] = [
            'identity'=> $identity,
            'identity_type'=> $identity_type,
            'name'=> $name,
        ];
        
        $request = new AlipayFundTransUniTransferRequest();
        $request->setBizContent(json_encode($bizContent));
        
        $result = static::aop()->execute($request);
        
        return static::response($result, $request);
    }

    /**
     * 电脑网站下单：返回支付二维码
     * @param string    $outTradeNo     订单号
     * @param float     $totalAmount    订单金额
     * @param string    $notifyUrl      异步回调地址
     * @param string    $subject        订单标题
     * @param string    $returnUrl      同步回调地址
     * @param string(512)   $passbackParams     公用回传参数，如果请求时传递了该参数，则返回给商户时会回传该参数。支付宝会在异步通知时将该参数原样返回
     * @param int           $timeoutExpress     单位：分钟，该笔订单允许的最晚付款时间，逾期将关闭交易，取值：1 ~ 21600
     */
    public static function tradePagePayQr($outTradeNo, $totalAmount, $notifyUrl, $subject, $returnUrl = '', $passbackParams = '', $timeoutExpress = 10) {
        $bizContent = [
            'out_trade_no'=> $outTradeNo,
            'product_code'=> 'FAST_INSTANT_TRADE_PAY',
            'total_amount'=> $totalAmount,
            'subject'=> $subject,
            'qr_pay_mode'=> 4,
            'qrcode_width'=> 400,
        ];
        
        $request = new AlipayTradePagePayRequest();
        $request->setBizContent(json_encode($bizContent));
        $request->setNotifyUrl($notifyUrl);
        if($returnUrl) $request->setReturnUrl($returnUrl);
        
        return static::aop()->pageExecute($request);
    }
}