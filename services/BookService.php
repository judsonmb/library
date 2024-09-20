<?php

namespace app\services;

use app\models\Book;
use app\models\BookForm;
use yii\base\Exception;

class BookService
{
    /**
     * List books with pagination and filtering.
     *
     * @param int $limit The number of records to return.
     * @param int $offset The offset for pagination.
     * @param string $orderBy The field to order by.
     * @param string|null $filterTitle The title to filter by.
     * @param string|null $filterAuthor The author to filter by.
     * @param string|null $filterIsbn The ISBN to filter by.
     * @return Book[] The list of books.
     */
    public function listBooks(
        int $limit,
        int $offset,
        string $orderBy,
        ?string $filterTitle,
        ?string $filterAuthor,
        ?string $filterIsbn
    ): array {
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
            case 'price':
                $query->orderBy(['price' => SORT_ASC]);
                break;
            case 'title':
            default:
                $query->orderBy(['title' => SORT_ASC]);
                break;
        }

        return $query->offset($offset)->limit($limit)->all();
    }

    /**
     * Register a new book.
     *
     * @param BookForm $model The model containing book data.
     * @return Book The created book.
     * @throws Exception If the book cannot be created.
     */
    public function register(BookForm $model): Book
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
