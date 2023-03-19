<?php

namespace App\Model;

use App\Helper\Database;

class Invoice
{
    public function getInvoice($id)
    {
        $dbh = Database::connect();
        $stmt = $dbh->prepare('SELECT * FROM invoices WHERE id = :id');
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getInvoices()
    {
        $dbh = Database::connect();
        $stmt = $dbh->prepare('SELECT * FROM invoices');
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function createInvoice($request)
    {
        $dbh = Database::connect();
        $stmt = $dbh->prepare('INSERT INTO invoices (customer_id, subtotal, taxable, tax_rate, tax_due, other, total, comments, paid, due_date) VALUES (:customer_id, :subtotal, :taxable, :tax_rate, :tax_due, :other, :total, :comments, :paid, :due_date)');

        $data = [
            'customer_id' => $request->customer_id,
            'subtotal' => $request->subtotal,
            'taxable' => $request->taxable,
            'tax_rate' => $request->tax_rate,
            'tax_due' => $request->tax_due,
            'other' => $request->other,
            'total' => $request->total,
            'comments' => $request->comments,
            'paid' => $request->paid,
            'due_date' => $request->due_date,
        ];

        if ($stmt->execute($data)) {
            return $dbh->lastInsertId();
        }

        return false;
    }
}
