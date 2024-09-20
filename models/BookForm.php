<?php

namespace app\models;

use yii\base\Model;

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
            ['isbn', 'string', 'max' => 13],
            ['isbn', 'unique', 'targetClass' => Book::class],
            ['title', 'string', 'max' => 255],
            ['author', 'string', 'max' => 255],
            ['price', 'number', 'min' => 0],
            ['stock', 'integer', 'min' => 0],
        ];
    }


    public function attributeLabels()
    {
        return [
            'isbn' => 'ISBN',
            'title' => 'Title',
            'author' => 'Author',
            'price' => 'Price',
            'stock' => 'Stock',
        ];
    }
}
