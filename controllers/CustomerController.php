<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\CustomerForm;
use app\models\Customer;

class CustomerController extends Controller
{
    public function actionCreate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new CustomerForm();
        $request = Yii::$app->request->post();

        $model->load($request, '');

        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;
            return ['error' => $model->getErrors()];
        }

        $customer = new Customer();
        $customer->name = $model->name;
        $customer->document = $model->document;
        $customer->zip_code = $model->zip_code;
        $customer->street = $model->street;
        $customer->number = $model->number;
        $customer->city = $model->city;
        $customer->state = $model->state;
        $customer->complement = $model->complement;
        $customer->gender = $model->gender;
        $customer->created_at = time();
        $customer->updated_at = time();

        if ($customer->save()) {
            return ['message' => 'Customer registered successfully.', 'customer_id' => $customer->id];
        } else {
            Yii::$app->response->statusCode = 500;
            return ['error' => 'Error registering customer.'];
        }
    }
}
