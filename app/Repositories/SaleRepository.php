<?php

namespace App\Repositories;

use App\Models\NewSale;
use App\Models\NewSaleProduct;
use App\Repositories\Interfaces\SaleInterface;
use Carbon\Carbon;

class SaleRepository implements SaleInterface
{
    public function store($data)
    {
        $total_qty = 0;
        $total_bill = 0;
        $total_before_discount = 0.0;
        for ($i = 0; $i < count($data['item_qty']); $i++) {
            $total_qty += (int) $data['item_qty'][$i];
            $total_before_discount += $data['item_qty'][$i] * $data['sale_price'][$i];
        }
        $total_bill = ($total_before_discount - (isset($data['sale_discount']) ? $data['sale_discount'] : 0)) * (isset($data['entire_vat']) ? $data['entire_vat'] : 1) + (isset($data['tax_stamp']) ? $data['tax_stamp'] / 100 : 0);
        $newSale = NewSale::create([
            'date' => isset($data['date']) ? Carbon::parse($data['date'])->format('Y-m-d') : '',
            'customer_id' => isset($data['customer_id']) ? $data['customer_id'] : '',
            'retailer_id' => auth()->user()->id,
            'cash_type' => isset($data['cash_type']) ? $data['cash_type'] : '',
            'entire_vat' => isset($data['entire_vat']) ? $data['entire_vat'] : '',
            'shipping_cost' => isset($data['shipping_cost']) ? $data['shipping_cost'] : '',
            'discount' => isset($data['sale_discount']) ? $data['sale_discount'] : '',
            'tax_stamp' => isset($data['tax_stamp']) ? $data['tax_stamp'] : '',
            'sale_note' => isset($data['sale_note']) ? $data['sale_note'] : '',
            'staff_note' => isset($data['staff_note']) ? $data['staff_note'] : '',
            'total_qty' => $total_qty,
            'total_bill' => $total_bill,
        ]);
        $loop_iterations = count($data['item_qty']);
        for ($i = 0; $i < $loop_iterations; $i++) {
            NewSaleProduct::create([
                'sale_id' => $newSale->id,
                'reference_no' => isset($data['article_number']) ? $data['article_number'][$i] : '',
                'quantity' => isset($data['item_qty']) ? $data['item_qty'][$i] : 0,
                'sale_price' => isset($data['sale_price']) ? $data['sale_price'][$i] : 0,
                'discount' => isset($data['discount']) ? $data['discount'][$i] : 0,
                'vat' => isset($data['vat']) ? $data['vat'][$i] : 0,
                'total_with_discount' => isset($data['sale_total_with_discount']) ? $data['sale_total_with_discount'][$i] : 0,
                'total_without_discount' => isset($data['sale_total_without_discount']) ? $data['sale_total_without_discount'][$i] : 0,
            ]);
        }
        return true;
    }
}
