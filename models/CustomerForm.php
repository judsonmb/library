<?php

namespace app\models;

use yii\base\Model;

class CustomerForm extends Model
{
    public $name;
    public $document;
    public $zip_code;
    public $street;
    public $number;
    public $city;
    public $state;
    public $complement;
    public $gender;

    public function rules()
    {
        return [
            [['name', 'document', 'zip_code', 'street', 'number', 'city', 'state', 'gender'], 'required'],
            ['name', 'string', 'max' => 255],
            ['document', 'string', 'length' => 11],
            ['document', 'match', 'pattern' => '/^\d{11}$/', 'message' => 'The document must contain only numbers.'],
            ['zip_code', 'string', 'max' => 10],
            ['gender', 'in', 'range' => ['M', 'F'], 'message' => 'Gender must be "M" or "F".'],
            [['street', 'number', 'city', 'state', 'complement'], 'string'],
        ];
    }
}
