<?php
namespace common\models;

use Yii;

/**
 * @link Paypal
 */
class Paypal {

    /**
     * DB table name.
     * @var type
     */
    public $paypalemail;     // e-mail продавца
    public $returnUrl;
    public $canselUrl;
    public $currency;               // валюта
    public $total;
    public $order_id;
    public $sign;
    public $item_name;
    public $amount;
    public $item_number;
    public $custom;
    public $invoice;
    public $shipping;
    public $shipping2;

    /**
     * Magic method __construct
     *
     * @inheritdoc
     * @param $isSandbox boolean e.g [[true test version(PayPal Sandbox), false orgin]]
     * @return string [[html form]]
     */
    function __construct($isSandbox = true) {
        if($isSandbox) {
            $this->paypalUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';    //   sandbox
        } else {
            $this->paypalUrl = 'https://www.paypal.com/cgi-bin/webscr';          //  live
        }
    }

    /**
     * Request
     *
     * @param $params array
     * @return void
     */
    public function request($params) {
        $this->paypalemail = 'test@gmail.com';
        $this->currency = 'USD';
        $this->returnUrl = 'http://' . $_SERVER['SERVER_NAME'] . Yii::$app->request->baseUrl . '/order/success/' . $params['order_id'];
        $this->canselUrl = 'http://' . $_SERVER['SERVER_NAME'] . Yii::$app->request->baseUrl . '/order/cancel';
        $this->total = (isset($params['Total']) && floatval($params['Total'])) ? $params['Total'] : 0;
        $this->shipping = (isset($params['shipping']) && floatval($params['shipping'])) ? $params['shipping'] : 0;
        $this->amount = (isset($params['amount']) && floatval($params['amount'])) ? $params['amount'] : 0;
        $this->shipping2 = (isset($params['shipping2']) && $params['shipping2']) ? $params['shipping2'] : '';
        $this->order_id = (isset($params['order_id']) && intval($params['order_id'])) ? $params['order_id'] : 0;
        $this->sign = (isset($params['sign']) && intval($params['sign'])) ? $params['sign'] : 0;
        $this->item_name = (isset($params['item_name']) && $params['item_name']) ? $params['item_name'] : '';
        $this->item_number = (isset($params['item_number']) && $params['item_number']) ? $params['item_number'] : '';
        $this->custom = (isset($params['custom']) && $params['custom']) ? $params['custom'] : '';
        $this->invoice = (isset($params['invoice']) && $params['invoice']) ? $params['invoice'] : '';
    }

    /**
     * Response status
     *
     * @param $post array $_POST
     * @return boolean status e.g [[true ok, false fail]]
     */
    public function response($post) {
        $email = '';
        $value = '';
        $status = 0;
        $request = "cmd=_notify-validate";

        foreach ($_POST as $varname => $varvalue) {
            $email .= "$varname: $varvalue\n";
            if (function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc()) {
                $varvalue = urlencode(stripslashes($varvalue));
            } else {
                $value = urlencode($value);
            }
            $request .= "&$varname=$varvalue";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->paypalUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        switch ($result) {
            case "VERIFIED":
                // ok
                $status = true;
                break;
            case "INVALID":
                // ошибка
                $status = false;
                break;
            default:
                // в других случаях
        }
        return $status;
    }

    /**
     * Response status
     *
     * @param $post array $_POST
     * @return boolean status e.g [[true ok, false fail]]
     */
    public function responseForUpdate($post) {
        $email = '';
        $value = '';
        $status = 0;
        $request = "cmd=_notify-validate";

        foreach ($_POST as $varname => $varvalue) {
            $email .= "$varname: $varvalue\n";
            if (function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc()) {
                $varvalue = urlencode(stripslashes($varvalue));
            } else {
                $value = urlencode($value);
            }
            $request .= "&$varname=$varvalue";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->paypalUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        switch ($result) {
            case "VERIFIED":
                $status = true;
                break;
            case "INVALID":
                // ошибка
                $status = false;
                break;
            default:
                // в других случаях
        }
        return $status;
    }

}