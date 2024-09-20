<?php

namespace app\services;

use app\models\Book;
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
        $book->created_at = time();
        $book->updated_at = time();

        if (!$book->save()) {
            throw new Exception('Error while creating book: ' . implode(", ", $book->getFirstErrors()));
        }

        return $book;
    }

    public function listBooks($limit, $offset, $orderBy, $filterTitle, $filterAuthor, $filterIsbn)
    {
        $query = Book::find();

        if ($filterTitle) {
            $query->andWhere(['like', 'title', $filterTitle]);
        }

        if ($filterAuthor) {
            $query->andWhere(['like', 'author', $filterAuthor]);
        }

        if ($filterIsbn) {
            $query->andWhere(['isbn' => $filterIsbn]);
        }

        switch ($orderBy) {
            case 'title':
                $query->orderBy(['title' => SORT_ASC]);
                break;
            case 'price':
                $query->orderBy(['price' => SORT_ASC]);
                break;
            default:
                $query->orderBy(['title' => SORT_ASC]);
                break;
        }

        return $query->offset($offset)->limit($limit)->all();
    }
}
