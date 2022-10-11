<?php

use Database\Seeders\BalanceCategorySeeder;
use Database\Seeders\BankListSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BankListSeeder::class);
        $this->call(BalanceCategorySeeder::class);

    }
}
