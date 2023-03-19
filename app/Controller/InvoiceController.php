<?php

namespace App\Controller;

use App\Model\Invoice;
use App\Model\InvoiceItem;
use App\Model\Product;

class InvoiceController
{
    public function index($request)
    {
        readfile('views/index.html');
    }

    public function indexJson($request)
    {
        $invoices = (new Invoice())->getInvoices();

        return json_encode($invoices);
    }

    public function show($request)
    {
        readfile('views/invoice.html');
    }

    public function showJson($request)
    {
        $invoice = (new Invoice())->getInvoice($request['id']);

        return json_encode($invoice);
    }

    public function store($request)
    {
        $request = json_decode($request);

        $invoice = new Invoice();
        $invoice_id = $invoice->createInvoice($request);

        if ($invoice_id) {
            $invoiceItem = new InvoiceItem();

            foreach ($request->items as $item) {
                $product = (new Product())->getProduct($item);
                $itemData = (object) [
                    'invoice_id' => $invoice_id,
                    'product_id' => $item,
                    'amount' => $product->amount,
                ];

                $invoiceItem->createInvoiceItem($itemData);
            }

            return json_encode([
                'status' => 200,
                'message' => 'Invoice created successfully'
            ]);
        }

        return json_encode([
            'status' => 413,
            'message' => 'Invoice creation failed'
        ]);
    }
}
