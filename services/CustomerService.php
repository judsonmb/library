<?php

namespace app\services;

use app\models\Customer;
use app\models\CustomerForm;
use yii\base\Exception;

class CustomerService
{
    /**
     * List customers with pagination and filtering.
     *
     * @param int $limit The number of records to return.
     * @param int $offset The offset for pagination.
     * @param string $orderBy The field to order by.
     * @param string|null $filterName The name to filter by.
     * @param string|null $filterDocument The document to filter by.
     * @return Customer[] The list of customers.
     */
    public function listCustomers(
        int $limit,
        int $offset,
        string $orderBy,
        ?string $filterName,
        ?string $filterDocument
    ): array {
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

    /**
     * Register a new customer.
     *
     * @param CustomerForm $model The model containing customer data.
     * @return int The ID of the created customer.
     * @throws Exception If the customer cannot be created.
     */
    public function register(CustomerForm $model): int
    {
        $customer = new Customer();
        $customer->name = $model->name;
        $customer->document = $model->document;
        $customer->zip_code = str_replace('-', '', $model->zip_code);
        $customer->street = $model->street;
        $customer->number = $model->number;
        $customer->city = $model->city;
        $customer->state = $model->state;
        $customer->complement = $model->complement;
        $customer->gender = $model->gender;

        if (!$customer->save()) {
            throw new Exception('Error while creating customer: ' . implode(", ", $customer->getFirstErrors()));
        }

        return $customer->id;
    }
}
