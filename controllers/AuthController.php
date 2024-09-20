<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use app\models\RegisterForm;
use app\models\LoginForm;
use app\services\AuthService;

class AuthController extends Controller
{
    private $authService;

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
            $user = $this->authService->register($model->username, $model->password, $model->confirm_password);
            return ['message' => 'Usuário criado com sucesso.', 'user_id' => $user->id];
        } catch (\Exception $e) {
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
            return ['error' => $model->getErrors()];
        }

        try {
            $token = $this->authService->login($model->username, $model->password);
            return ['token' => $token];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
