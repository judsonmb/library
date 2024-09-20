<?php

namespace app\controllers;

use app\models\BookForm;
use app\services\BookService;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\web\Response;

class BookController extends Controller
{
    private BookService $bookService;

    public function __construct($id, $module, BookService $bookService, array $config = [])
    {
        $this->bookService = $bookService;
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
        $orderBy = $request->get('order_by', 'title');
        $filterTitle = $request->get('filter_title', null);
        $filterAuthor = $request->get('filter_author', null);
        $filterIsbn = $request->get('filter_isbn', null);

        try {
            $books = $this->bookService->listBooks($limit, $offset, $orderBy, $filterTitle, $filterAuthor, $filterIsbn);

            if (empty($books)) {
                Yii::$app->response->statusCode = 404;

                return ['message' => 'No results found'];
            }

            return ['data' => $books];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;

            return ['message' => $e->getMessage()];
        }
    }

    public function actionCreate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new BookForm();
        $request = Yii::$app->request->post();

        $model->load($request, '');

        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;

            return ['errors' => $model->getErrors()];
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

            return ['message' => $e->getMessage()];
        }
    }
}
