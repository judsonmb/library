<?php

namespace app\services;

use app\models\Book;
use app\models\BookForm;
use Yii;
use yii\base\Exception;

class BookService
{
    public function register(BookForm $model)
    {
        $book = new Book();
        $book->isbn = $model->isbn;
        $book->title = $model->title;
        $book->author = $model->author;
        $book->price = $model->price;
        $book->stock = $model->stock;

        if (!$book->save()) {
            throw new Exception('Error while creating book: ' . implode(", ", $book->getFirstErrors()));
        }

        return $book;
    }
}
