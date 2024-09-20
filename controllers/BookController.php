<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\auth\HttpBearerAuth;
use app\services\BookService;
use app\models\BookForm;

class BookController extends Controller
{
    private $bookService;

    public function __construct($id, $module, BookService $bookService, $config = [])
    {
        $this->bookService = $bookService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            [
                'class' => HttpBearerAuth::class,
                'only' => ['create'],
            ],
        ];
    }

    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new BookForm();
        $request = Yii::$app->request->post();

        $model->load($request, '');

        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;
            return [
                'errors' => $model->getErrors(),
            ];
        }

        try {
            $book = $this->bookService->register($model);
            Yii::$app->response->statusCode = 201;
            return [
                'message' => 'Book created successfully.',
                'book_id' => $book->id,
            ];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;
            return [
                'message' => $e->getMessage(),
            ];
        }
    }
}
