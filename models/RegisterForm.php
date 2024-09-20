<?php

namespace app\models;

use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $password;
    public $confirm_password;

    public function rules()
    {
        return [
            [['username', 'password', 'confirm_password'], 'required'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Este nome de usuário já está em uso.'],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => "As senhas não correspondem."],
        ];
    }
}
