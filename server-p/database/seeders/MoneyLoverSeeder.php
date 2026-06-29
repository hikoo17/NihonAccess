<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class MoneyLoverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan bahasa Indonesia agar data nama lebih relevan
        $now = now();

        // --- 1. SEED TABLE: currencies ---
        $currencies = [
            ['name' => 'Indonesian Rupiah', 'symbol' => 'Rp', 'code' => 'IDR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'US Dollar', 'symbol' => '$', 'code' => 'USD', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Euro', 'symbol' => '€', 'code' => 'EUR', 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('currencies')->insert($currencies);
        // Ambil semua ID currency untuk relasi ke wallet
        $currencyIds = DB::table('currencies')->pluck('id')->toArray();


        // --- 2. SEED TABLE: categories ---
        $categoriesData = [
            // Expenses
            ['name' => 'Makanan & Minuman', 'icon' => 'fa-utensils', 'type' => 'EXPENSE'],
            ['name' => 'Transportasi', 'icon' => 'fa-car', 'type' => 'EXPENSE'],
            ['name' => 'Belanja', 'icon' => 'fa-shopping-bag', 'type' => 'EXPENSE'],
            ['name' => 'Tagihan & Utilitas', 'icon' => 'fa-file-invoice-dollar', 'type' => 'EXPENSE'],
            ['name' => 'Hiburan', 'icon' => 'fa-gamepad', 'type' => 'EXPENSE'],
            // Incomes
            ['name' => 'Gaji', 'icon' => 'fa-wallet', 'type' => 'INCOME'],
            ['name' => 'Investasi', 'icon' => 'fa-chart-line', 'type' => 'INCOME'],
            ['name' => 'Gaji Sampingan', 'icon' => 'fa-laptop-code', 'type' => 'INCOME'],
            ['name' => 'Hadiah', 'icon' => 'fa-gift', 'type' => 'INCOME'],
        ];

        foreach ($categoriesData as $cat) {
            DB::table('categories')->insert([
                'name' => $cat['name'],
                'icon' => $cat['icon'],
                'type' => $cat['type'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $categoryIds = DB::table('categories')->pluck('id')->toArray();


        // --- 3. SEED TABLE: users, wallets, & transactions (Bulk Looping) ---
        $totalUsers = 50; // Kamu bisa ubah jumlahnya di sini jika ingin lebih banyak
        
        for ($i = 0; $i < $totalUsers; $i++) {
            // Pembuatan User
            $userId = DB::table('users')->insertGetId([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'), // Default password untuk semua user dummy
                'remember_token' => Str::random(10),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => $now,
            ]);

            // Setiap user akan dibuatkan 1 sampai 3 wallet secara acak (misal: Dompet Utama, Tabungan, Pajak)
            $walletCount = rand(1, 3);
            $walletNames = ['Dompet Utama', 'Tabungan', 'Cash / Saku', 'Kartu Kredit', 'Investasi'];
            shuffle($walletNames);

            for ($w = 0; $w < $walletCount; $w++) {
                $walletId = DB::table('wallets')->insertGetId([
                    'user_id' => $userId,
                    'currency_id' => $faker->randomElement($currencyIds),
                    'name' => $walletNames[$w],
                    'created_at' => $faker->dateTimeBetween('-5 months', 'now'),
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]);

                // Setiap wallet akan memiliki 15 sampai 40 data transaksi acak
                $transactionCount = rand(15, 40);
                $transactions = [];

                for ($t = 0; $t < $transactionCount; $t++) {
                    $randomCategory = $faker->randomElement($categoryIds);
                    
                    // Menentukan range jumlah uang agar terlihat masuk akal
                    $amount = $faker->randomElement([
                        rand(10000, 100000),   // Jajan/bensin kecil
                        rand(150000, 500000),  // Belanja bulanan/hiburan tengah
                        rand(1500000, 7000000) // Nominal besar biasanya untuk Gaji/Gaji sampingan
                    ]);

                    $transactions[] = [
                        'category_id' => $randomCategory,
                        'wallet_id' => $walletId,
                        'amount' => $amount,
                        'note' => $faker->optional(0.7)->sentence(rand(2, 5)), // 70% transaksi ada catatannya
                        'date' => $faker->dateTimeBetween('-4 months', 'now')->format('Y-m-d'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // Insert transaksi dalam bentuk batch per wallet agar performa eksekusi cepat
                DB::table('transactions')->insert($transactions);
            }
        }
    }
}