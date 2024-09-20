<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    public function actionError()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $error = Yii::$app->errorHandler->exception;

        if ($error) {
            return [
                'status' => 'error',
                'code' => $error->statusCode,
                'message' => $error->getMessage(),
            ];
        }

        return [
            'status' => 'error',
            'code' => 500,
            'message' => 'An unknown error occurred.',
        ];
    }
}
