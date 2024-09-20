<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\RegisterForm;
use app\services\AuthService;
use Yii;
use yii\rest\Controller;
use yii\web\Response;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct($id, $module, AuthService $authService, $config = [])
    {
        $this->authService = $authService;
        parent::__construct($id, $module, $config);
    }

    public function actionRegister()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new RegisterForm();
        $request = Yii::$app->request->post();

        $model->load($request, '');

        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;

            return ['error' => $model->getErrors()];
        }

        try {
            $user = $this->authService->register($model);
            Yii::$app->response->statusCode = 201;

            return [
                'message' => 'User created successfully.',
                'user_id' => $user->id,
            ];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;

            return ['error' => $e->getMessage()];
        }
    }

    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new LoginForm();
        $request = Yii::$app->request->post();

        $model->load($request, '');

        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;

            return ['error' => $model->getErrors()];
        }

        try {
            $token = $this->authService->login($model->username, $model->password);
            return ['token' => $token];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 401;

            return ['error' => $e->getMessage()];
        }
    }
}
