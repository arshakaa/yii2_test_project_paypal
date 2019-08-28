<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Paypal;

/**
 * Order controller
 */
class OrderController extends Controller
{
    /**
     * @var bool boolean e.g [[true test version(PayPal Sandbox), false orgin]]
     */
    private $_isSandbox = false;
    private $_paypalParams = [
        "Total" => 1,
        "amount" => 500,
        "order_id" => 1,
        "item_number" => 1,
        "item_name" => 'Yii2.dev Order ID: 1',
    ];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'success', 'false', 'cancel'],
                'rules' => [
                    // deny all POST requests
                    [
                        'allow' => false,
                        'verbs' => ['POST']
                    ],
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $paypal = new Paypal($this->_isSandbox);
        $paypal->request($this->_paypalParams);

        return $this->render('index', [
            'paypal' => $paypal,
        ]);
    }

    /**
     * Sccess paypal page.
     *
     * @return mixed
     */
    public function actionSuccess($id)
    {
      if (Yii::$app->request->isPost) {
            $paypal = new Paypal($this->_isSandbox);
            if ($this->_paypalParams['order_id'] == $id) {
                $quantity = Yii::$app->request->post('quantity', 0);
                $mc_currency = Yii::$app->request->post('mc_currency', '');
                $payment_gross = Yii::$app->request->post('payment_gross', 0);
                $payment_gross = floatval($payment_gross);
                if ($this->_paypalParams['amount'] * $this->_paypalParams['Total'] == $payment_gross && $mc_currency == 'USD' && $quantity == $this->_paypalParams['Total']) {
                    if ($status = $paypal->response(Yii::$app->request->post()) == true) {
                        $path = Yii::getAlias('@webroot') . '/order-files';
                        $file = $path . '/order-1.zip';

                        if (file_exists($file)) {
                            Yii::$app->response->sendFile($file);
                        }


                        $paypal->request($this->_paypalParams);

                        Yii::$app->session->setFlash('success-paypal', 'Success!');

                        return $this->render('/index', [
                            'paypal' => $paypal,
                        ]);
                    }
                }
            }
        }
        return $this->redirect(['/order/false']);
    }

    /**
     * False paypal page.
     *
     * @return mixed
     */
    public function actionFalse()
    {
        Yii::$app->session->setFlash('error-paypal', 'error payment!');
        return $this->redirect(['/order']);
    }

    /**
     * Cancel paypal page.
     *
     * @return mixed
     */
    public function actionCancel()
    {
        Yii::$app->session->setFlash('cancele-paypal', 'error payment canceled!');
        return $this->redirect(['/order']);
    }
}
