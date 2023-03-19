<?php

namespace App\Model;

use App\Helper\Database;

class Customer
{
    public function getCustomers()
    {
        $dbh = Database::connect();
        $stmt = $dbh->prepare('SELECT * FROM customers');
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getCustomer($id)
    {
        $dbh = Database::connect();
        $stmt = $dbh->prepare('
            SELECT
                customers.id,
                customers.name,
                customers.company_name,
                CASE WHEN customers.mobile IS NULL THEN customers.work ELSE customers.home END AS phone,
                customer_addresses.line1,
                customer_addresses.line2,
                customer_addresses.city,
                customer_addresses.state,
                customer_addresses.zip
            FROM
                customers
            LEFT JOIN customer_addresses 
                ON customer_addresses.customer_id = customers.id AND
                CASE
                    WHEN
                        NOT EXISTS (SELECT * FROM customer_addresses WHERE type = "billing" AND customer_addresses.customer_id = customers.id)
                    THEN
                        customer_addresses.type = "residential"
                    ELSE
                        customer_addresses.type = "billing"
                END
            LEFT JOIN customer_contacts
                ON customer_contacts.customer_id = customers.id
            WHERE
                customers.id = :id'
        );
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
