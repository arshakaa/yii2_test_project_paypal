<?php

use \yii\helpers\Html;
use \yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $paypal \common\models\Paypal */

$this->title = $paypal->item_name;
$this->params['breadcrumbs'][] = 'Order';
?>
<div class="site-index">
    <div class="body-content">
        <div class="col-md-12">
            <?php
            $form = ActiveForm::begin([
                'id' => 'login-form',
                'action' => $paypal->paypalUrl,
                'options' => [
                    'class' => 'form-horizontal',
                ],
            ]) ?>

            <div class="row">
                <?php if(Yii::$app->session->hasFlash('success-paypal')): ?>
                    <h2 class="alert alert-success"><?php echo Yii::$app->session->getFlash('success-paypal'); ?></h2>
                <?php elseif(Yii::$app->session->hasFlash('error-paypal')): ?>
                    <h2 class="alert alert-danger"><?php echo Yii::$app->session->getFlash('error-paypal'); ?></h2>
                <?php elseif(Yii::$app->session->hasFlash('cancele-paypal')): ?>
                    <h2 class="alert alert-warning"><?php echo Yii::$app->session->getFlash('cancele-paypal'); ?></h2>
                <?php endif; ?>
            </div>

            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="no_shipping" value="1">
            <input type="hidden" name="return" value="<?php echo $paypal->returnUrl; ?>">
            <input type="hidden" name="rm" value="2">
            <input type="hidden" name="cancel_return" value="<?php echo $paypal->canselUrl; ?>">
            <input type="hidden" name="currency_code" value="<?php echo $paypal->currency; ?>">
            <input type="hidden" name="verify_sign" value="<?php echo $paypal->sign; ?>">
            <input type="hidden" name="custom" value="<?php echo $paypal->custom; ?>" />
            <input type="hidden" name="payer_business_name" value="">
            <input type="hidden" name="invoice" value="<?php echo $paypal->invoice; ?>">
            <input type="hidden" name="item_name_x" value="mail_1">
            <input type="hidden" name="shipping" value="<?php echo $paypal->shipping; ?>">
            <input type="hidden" name="shipping2" value="<?php echo $paypal->shipping2; ?>">
            <input type="hidden" name="total" value="<?php echo $paypal->total; ?>">
            <input type="hidden" name="item_number" value="<?php echo $paypal->item_number; ?>">
            <input type="hidden" name="business" value="<?php echo $paypal->paypalemail; ?>">
            <input type="hidden" name="item_name" value="<?php echo $paypal->item_name; ?>">
            <input type="hidden" name="amount" value="<?php echo $paypal->amount; ?>">

            <div class="form-group">
                <label for="business">business:</label>
                <input disabled="disabled" id="business" type="text" class="form-control" name="info_business" value="<?php echo $paypal->paypalemail; ?>">
            </div>

            <div class="form-group">
                <label for="item_name">item name:</label>
                <input disabled="disabled" id="item_name" type="text" class="form-control" name="info_item_name" value="<?php echo $paypal->item_name; ?>">
            </div>

            <div class="form-group">
                <label for="amount">amount:</label>
                <input disabled="disabled" type="text" class="form-control" name="info_amount" value="<?php echo $paypal->amount; ?>">
            </div>

            <div class="form-group">
                <label for="currency">currency:</label>
                <input disabled="disabled" type="text" class="form-control" name="info_currency" value="<?php echo $paypal->currency; ?>">
            </div>

            <div class="form-group">
                <?php echo Html::submitButton('Order Pay', ['class' => 'btn btn-primary btn-lg']); ?>
            </div>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
