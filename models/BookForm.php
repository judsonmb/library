<?php

namespace app\models;

use yii\base\Model;
use app\models\Book;

class BookForm extends Model
{
    public $isbn;
    public $title;
    public $author;
    public $price;
    public $stock;

    public function rules()
    {
        return [
            [['isbn', 'title', 'author', 'price', 'stock'], 'required'],
            ['isbn', 'string'],
            ['isbn', 'unique', 'targetClass' => Book::class],
            ['isbn', 'validateIsbn'],
            ['title', 'string', 'max' => 255],
            ['author', 'string', 'max' => 255],
            ['price', 'number', 'min' => 0],
            ['stock', 'integer', 'min' => 0],
        ];
    }

    public function validateIsbn($attribute, $params)
    {
        $url = "https://brasilapi.com.br/api/isbn/v1/{$this->isbn}";
        $response = @file_get_contents($url);

        if ($response === false || strpos($http_response_header[0], '404') !== false) {
            $this->addError($attribute, 'Invalid ISBN.');
        }
    }
}
