<?php

namespace Database\Seeders\BookTransaction;

use App\Models\BookTransaction\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        TransactionType::insert([
            [
                'name' => 'KEDATANGAN',
                'description' => 'Barang masuk dari percetakan',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'PENGAMBILAN',
                'description' => 'Barang diambil untuk dijual oleh sales maupun owner',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'RETUR',
                'description' => 'Barang dikembalikan ke percetakan',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'MUTASI',
                'description' => 'Barang yang akan di ambil oleh percetakan dan di ubah di pindahkan ke distributor lain',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
