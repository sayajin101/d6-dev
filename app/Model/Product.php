<?php

namespace App\Model;

use App\Helper\Database;

class Product
{
    public function getProduct($id)
    {
        $dbh = Database::connect();
        $stmt = $dbh->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getProducts()
    {
        $dbh = Database::connect();
        $stmt = $dbh->prepare('SELECT * FROM products');
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}