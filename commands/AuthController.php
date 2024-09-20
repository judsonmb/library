<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\RegisterForm;
use app\services\AuthService;
use Yii;

class AuthController extends Controller
{
    private $authService;

    public function __construct($id, $module, AuthService $authService, $config = [])
    {
        $this->authService = $authService;
        parent::__construct($id, $module, $config);
    }

    public function actionRegister($username, $password, $confirmPassword)
    {
        $model = new RegisterForm();
        $model->username = $username;
        $model->password = $password;
        $model->confirm_password = $confirmPassword;

        if (!$model->validate()) {
            echo "Erro de validação:\n";
            print_r($model->getErrors());
            return ExitCode::DATAERR;
        }

        try {
            $user = $this->authService->register($model->username, $model->password, $model->confirm_password);
            echo "Usuário '{$user->username}' criado com sucesso com ID {$user->id}.\n";
            return ExitCode::OK;
        } catch (\Exception $e) {
            echo "Erro ao criar usuário: " . $e->getMessage() . "\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
