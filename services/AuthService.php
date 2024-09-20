<?php

namespace app\services;

use app\models\User;
use Yii;
use yii\base\Exception;
use app\models\RegisterForm;

class AuthService
{
    /**
     * Register a new user.
     *
     * @param RegisterForm $model The model containing registration data.
     * @return User The created user.
     * @throws Exception If the user cannot be created.
     */
    public function register(RegisterForm $model): User
    {
        $user = new User();
        $user->username = $model->username;
        $user->password_hash = Yii::$app->security->generatePasswordHash($model->password);
        $user->auth_key = Yii::$app->security->generateRandomString();

        if (!$user->save()) {
            throw new Exception('Error while creating user: ' . implode(", ", $user->getFirstErrors()));
        }

        return $user;
    }

    /**
     * Authenticate a user and generate a token.
     *
     * @param string $username The username of the user.
     * @param string $password The password of the user.
     * @return string The generated token.
     * @throws Exception If the credentials are invalid.
     */
    public function login(string $username, string $password): string
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
