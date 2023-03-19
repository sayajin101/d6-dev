<?php

namespace App\Controller;

use App\Model\Customer;

class CustomerController
{
    public function indexJson($request)
    {
        $customers = (new Customer())->getCustomers();

        return json_encode($customers);
    }

    public function showJson($request)
    {
        $customer = (new Customer())->getCustomer($request['id']);

        return json_encode($customer);
    }
}
