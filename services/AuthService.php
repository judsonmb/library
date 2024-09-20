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
        $user = new User();
        $user->username = $username;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->auth_key = Yii::$app->security->generateRandomString();

        if (!$user->save()) {
            throw new Exception('Create user error: ' . implode(", ", $user->getFirstErrors()));
        }

        return $user;
    }


    public function login($username, $password)
    {
        $user = User::findByUsername($username);

        if (!$user || !Yii::$app->security->validatePassword($password, $user->password_hash)) {
            throw new Exception('Invalid Credentials.');
        }

        $token = Yii::$app->security->generateRandomString();
        $user->auth_key = $token;
        $user->save(false);

        return $token;
    }
}