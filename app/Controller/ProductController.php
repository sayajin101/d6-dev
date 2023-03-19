<?php

namespace App\Controller;

use App\Model\Product;

class ProductController
{   
    public function indexJson($request)
    {
        $products = (new Product())->getProducts();

        return json_encode($products);
    }

    public function showJson($request)
    {
        $products = (new Product())->getProduct($request['id']);

        return json_encode($products);
    }
}
