<?php

namespace app\services;

use app\models\CustomerForm;
use app\models\Customer;
use Yii;

class CustomerService
{
    public function listCustomers($limit, $offset, $orderBy, $filterName, $filterDocument)
    {
        $query = Customer::find();

        if ($filterName) {
            $query->andWhere(['like', 'name', $filterName]);
        }

        if ($filterDocument) {
            $query->andWhere(['document' => $filterDocument]);
        }

        switch ($orderBy) {
            case 'name':
                $query->orderBy(['name' => SORT_ASC]);
                break;
            case 'cpf':
                $query->orderBy(['cpf' => SORT_ASC]);
                break;
            case 'city':
                $query->orderBy(['city' => SORT_ASC]);
                break;
            default:
                $query->orderBy(['name' => SORT_ASC]);
                break;
        }

        return $query->offset($offset)->limit($limit)->all();
    }

    public function register(CustomerForm $model)
    {
        $customer = new Customer();
        $customer->name = $model->name;
        $customer->document = $model->document;
        $customer->zip_code = $model->zip_code;
        $customer->street = $model->street;
        $customer->number = $model->number;
        $customer->city = $model->city;
        $customer->state = $model->state;
        $customer->complement = $model->complement;
        $customer->gender = $model->gender;
        $customer->created_at = time();
        $customer->updated_at = time();

        if ($customer->save()) {
            return $customer->id;
        } else {
            throw new \Exception('Error registering customer.');
        }
    }
}
