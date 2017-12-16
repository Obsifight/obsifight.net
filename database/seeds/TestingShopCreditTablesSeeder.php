<?php

use Illuminate\Database\Seeder;

class TestingShopCreditTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Add user
      DB::table('users')->truncate();
      DB::table('users')->insert([
        'username' => 'Test',
        'email' => 'test@test.com',
        'password' => 'dd202cf35d550d12a536a277c8ada507159c7a05', // test
        'money' => 10,
        'ip' => '127.0.0.1',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      // attach role
      DB::table('role_user')->truncate();
      DB::table('role_user')->insert([
        'user_id' => 1,
        'role_id' => 1
      ]);
      DB::table('role_user')->insert([
        'user_id' => 2,
        'role_id' => 2
      ]);
      DB::table('users')->insert([
        'username' => 'Test2',
        'email' => 'test2@test.com',
        'password' => 'ccf689101ea907d07be40d597179860ddf59876e', // test
        'ip' => '127.0.0.1',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('users')->insert([
        'username' => 'Test3',
        'email' => 'test3@test.com',
        'password' => 'a6b0e773422c3cea807521560aed0cc5d924b4ae', // test
        'ip' => '127.0.0.1',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      DB::table('shop_credit_dedipass_histories')->truncate();
      DB::table('shop_credit_dedipass_histories')->insert([
        'code' => '1808USED',
        'rate' => 'FR-SMS-10',
        'payout' => 1.80,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      DB::table('shop_credit_paypal_histories')->truncate();
      DB::table('shop_credit_paypal_histories')->insert([
        'payment_amount' => 10,
        'payment_tax' => 0.8,
        'payment_id' => '47374DHD',
        'buyer_email' => 'paypal@buyer.com',
        'payment_date' => date('Y-m-d H:i:s'),
        'status' => 'COMPLETED',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      DB::table('shop_credit_hipay_histories')->truncate();
      DB::table('shop_credit_hipay_histories')->insert([
        'payment_amount' => 10.0,
        'payment_id' => '58DFDA4488963163',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      DB::table('shop_credit_paysafecard_histories')->truncate();
      DB::table('shop_credit_paysafecard_histories')->insert([
        'payment_amount' => 10.0,
        'payment_id' => '58DFDA4488963163',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      DB::table('shop_vouchers')->truncate();
      DB::table('shop_vouchers')->insert([
        'code' => "valid_code",
        'money' => 10,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      DB::table('shop_vouchers')->insert([
        'code' => "already_used",
        'money' => 10,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('shop_credit_histories')->insert([
        'user_id' => 1,
        'money' => 10,
        'amount' => 0,
        'transaction_type' => 'VOUCHER',
        'transaction_id' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('shop_vouchers_histories')->truncate();
      DB::table('shop_vouchers_histories')->insert([
        'voucher_id' => 2,
        'history_id' => 1,
        'user_id' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
    }
}
