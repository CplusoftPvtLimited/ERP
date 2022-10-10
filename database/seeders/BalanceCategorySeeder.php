<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalanceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'category' => 'Vehicle and transport Rental',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of Rents',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of maintainance and repair invoice',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of net wages',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of transport costs',
                'type' => 'debit'
            ],
            [
                'category' => 'Travel and Trip',
                'type' => 'debit'
            ],
            [
                'category' => 'Vehicle (repair,mainatanance)',
                'type' => 'debit'
            ],
            [
                'category' => 'Other',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of goods purchase invoices',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of invoices for purchase of raw materials',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of packaging purchase invoices',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of Subcontarcts',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of CNSS declarations',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of tax declarations',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of communication and marketing invoiices',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of fees',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of STEG / SONDEDE invoices',
                'type' => 'debit'
            ],
            [
                'category' => 'Payment of telecommunication invoices',
                'type' => 'debit'
            ],
            [
                'category' => 'Other operating expense regulations',
                'type' => 'debit'
            ],
            [
                'category' => 'Cash Payment of fixed asset acquisation invoices',
                'type' => 'debit'
            ],
            [
                'category' => 'Refunds of associated current accounts',
                'type' => 'debit'
            ],
            [
                'category' => 'Repayment of bank loans',
                'type' => 'debit'
            ],
            [
                'category' => 'Reayment of debts to suppliers of fixed assets',
                'type' => 'debit'
            ],
            [
                'category' => 'Repayment of other debts',
                'type' => 'debit'
            ],
            [
                'category' => 'Cash Sales collection',
                'type' => 'credit'
            ],
            [
                'category' => 'Collection of customer recievables',
                'type' => 'credit'
            ],
            [
                'category' => 'Receipts recieved from partners',
                'type' => 'credit'
            ],
            [
                'category' => 'Bank credit collection',
                'type' => 'credit'
            ],[
                'category' => 'Other Collections',
                'type' => 'credit'
            ],
        ];
        DB::table('balance_categories')->insert($data);
    }
}
