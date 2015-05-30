<?php

namespace app\modules\shop\controllers;

use app\modules\shop\models\Order;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

class CabinetController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProfile()
    {
        return $this->render('profile');
    }

    public function actionUpdate()
    {
        $userId = \Yii::$app->user->isGuest ? 0 : \Yii::$app->user->id;
        $orderId = \Yii::$app->request->post('orderId');
        if (intval($orderId) <= 0) {
            return $this->redirect(Url::previous('__shopOrderShowUrl'));
        }
        /** @var Order $order */
        if (null === $order = Order::findOne(['user_id' => $userId, 'id' => $orderId])) {
            return $this->redirect(Url::previous('__shopOrderShowUrl'));
        }

        if (!empty($order->customer)) {
            $customer = $order->customer;
            if ($customer->load(\Yii::$app->request->post())) {
                $customer->saveModelWithProperties(\Yii::$app->request->post());
            }
        }

        if (!empty($order->contragent)) {
            $contragent = $order->contragent;
            if ($contragent->load(\Yii::$app->request->post())) {
                $contragent->saveModelWithProperties(\Yii::$app->request->post());
            }
        }

        return $this->redirect(Url::previous('__shopOrderShowUrl'));
    }
}
?>