<?php

namespace Database\Seeders;

use App\Models\BankList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data= [
            [
                'title'=> 'ABC bank'
            ],
            [
                'title'=> 'Albaraka bank'
            ],
            [
                'title'=> 'Amen bank'
            ],
            [
                'title'=> 'ATB bank'
            ],
            [
                'title'=> 'Attijari bank'
            ],
            [
                'title'=> 'BIAT bank'
            ],
            [
                'title'=> 'BNA bank'
            ],
            [
                'title'=> 'BTE bank'
            ],
            [
                'title'=> 'BTK bank'
            ],
            [
                'title'=> 'BTL bank'
            ],
            [
                'title'=> 'Citi bank'
            ],
            [
                'title'=> "L'habitat bank"
            ],
            [
                'title'=> 'QNB bank'
            ],
            [
                'title'=> 'STB bank'
            ],
            [
                'title'=> 'Stusid bank'
            ],
            [
                'title'=> 'Tunisie bank'
            ],
            [
                'title'=> 'UBCI bank'
            ],
            [
                'title'=> 'UIB bank'
            ],
            [
                'title'=> 'Zitouna bank'
            ],
            ];
        DB::table('bank_lists')->insert($data);

    }
}
