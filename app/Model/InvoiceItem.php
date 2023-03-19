<?php

namespace App\Model;

use App\Helper\Database;

class InvoiceItem
{
    public function getInvoiceItemsByInceoiceId($invoice_id)
    {
        $dbh = Database::connect();
        $stmt = $dbh->prepare('SELECT * FROM invoice_items WHERE invoice_id = :invoice_id');
        $stmt->execute(['invoice_id' => $invoice_id]);
        
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function createInvoiceItem($request)
    {
        $dbh = Database::connect();
        $stmt = $dbh->prepare('INSERT INTO invoice_items (invoice_id, product_id, amount) VALUES (:invoice_id, :product_id, :amount)');

        $data = [
            'invoice_id' => $request->invoice_id,
            'product_id' => $request->product_id,
            'amount' => $request->amount,
        ];

        if ($stmt->execute($data)) {
            return true;
        }

        return false;
    }
}
