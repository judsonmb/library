<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\CustomerForm;
use app\services\CustomerService;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct($id, $module, CustomerService $customerService, $config = [])
    {
        $this->customerService = $customerService;
        parent::__construct($id, $module, $config);
    }

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

        try {
            $customerId = $this->customerService->register($model);
            Yii::$app->response->statusCode = 201;
            return ['message' => 'Customer created successfully.', 'customer_id' => $customerId];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;
            return ['error' => $e->getMessage()];
        }
    }
}
