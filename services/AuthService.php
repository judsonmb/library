<?php

namespace app\services;

use app\models\User;
use app\components\JwtAuth;
use Yii;
use yii\base\Exception;

class AuthService
{
    public function register($username, $password, $confirmPassword)
    {
        if ($password !== $confirmPassword) {
            throw new Exception('As senhas não coincidem.');
        }

        $user = new User();
        $user->username = $username;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->created_at = time();
        $user->updated_at = time();

        if (!$user->save()) {
            throw new Exception('Erro ao criar usuário: ' . implode(", ", $user->getFirstErrors()));
        }

        return $user;
    }


    public function login($username, $password)
    {
        $user = User::findByUsername($username);

        if (!$user || !Yii::$app->security->validatePassword($password, $user->password_hash)) {
            throw new Exception('Credenciais inválidas.');
        }

        $token = Yii::$app->security->generateRandomString();
        $user->auth_key = $token;
        $user->save(false);

        return $token;
    }
}