<?php

namespace app\models;

use yii\base\Model;
use app\models\Customer;

class CustomerForm extends Model
{
    public $name;
    public $document;
    public $zip_code;
    public $street;
    public $number;
    public $city;
    public $state;
    public $gender;
    public $complement;

    public function rules()
    {
        return [
            [['name', 'document', 'zip_code', 'street', 'number', 'city', 'state', 'gender'], 'required'],
            ['name', 'string', 'max' => 255],
            ['document', 'string', 'length' => [11, 11]],
            ['document', 'unique', 'targetClass' => Customer::class],
            ['document', 'match', 'pattern' => '/^\d{11}$/'],
            ['zip_code', 'string', 'max' => 10],
            ['zip_code', 'validateZipCode'],
            ['gender', 'in', 'range' => ['M', 'F'], 'message' => 'Gender must be "M" or "F".'],
            [['street', 'number', 'city', 'state', 'complement'], 'string'],
        ];
    }

    public function validateZipCode($attribute, $params)
    {
        $url = "https://brasilapi.com.br/api/cep/v2/{$this->zip_code}";
        $response = @file_get_contents($url);

        if ($response === false || strpos($http_response_header[0], '404') !== false) {
            $this->addError($attribute, 'Invalid zipcode');
        }
    }
}
