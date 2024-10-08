<?php

namespace app\controllers;

use app\models\CustomerForm;
use app\services\CustomerService;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\web\Response;

class CustomerController extends Controller
{
    private CustomerService $customerService;

    public function __construct($id, $module, CustomerService $customerService, array $config = [])
    {
        $this->customerService = $customerService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => HttpBearerAuth::class,
                'only' => ['index', 'create'],
            ],
        ];
    }

    public function actionIndex(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);
        $orderBy = $request->get('order_by', 'name');
        $filterName = $request->get('filter_name', null);
        $filterCpf = $request->get('filter_cpf', null);

        try {
            $customers = $this->customerService->listCustomers($limit, $offset, $orderBy, $filterName, $filterCpf);

            if (empty($customers)) {
                Yii::$app->response->statusCode = 404;

                return ['message' => 'No results found'];
            }

            return [
                'status' => 'success',
                'data' => $customers,
            ];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;

            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function actionCreate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new CustomerForm();
        $request = Yii::$app->request->post();

        $model->load($request, '');

        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;

            return ['status' => 'error', 'errors' => $model->getErrors()];
        }

        try {
            $customerId = $this->customerService->register($model);
            Yii::$app->response->statusCode = 201;

            return [
                'message' => 'Customer created successfully.',
                'customer_id' => $customerId,
            ];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;

            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
