<?php

use Illuminate\Database\Seeder;

class TestingUsersTablesSeeder extends Seeder
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
        'ip' => '127.0.0.1',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
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
      // Block ip
      DB::table('users_login_retries')->truncate();
      DB::table('users_login_retries')->insert([
        'ip' => '127.0.0.2',
        'count' => '10',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      // TwoFactorAuth
      DB::table('users_two_factor_auth_secrets')->truncate();
      DB::table('users_two_factor_auth_secrets')->insert([
        'secret' => str_random(20),
        'enabled' => 1,
        'user_id' => 2
      ]);
      DB::table('users_two_factor_auth_secrets')->insert([
        'secret' => str_random(20),
        'enabled' => 0,
        'user_id' => 3
      ]);
      // Confirm token
      DB::table('users_tokens')->truncate();
      DB::table('users_tokens')->insert([
        'type' => 'EMAIL',
        'user_id' => 1,
        'token' => '85ce3890-2c2c-11e7-ad60-a330f1f9660b',
        'used_ip' => null,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('users_tokens')->insert([
        'type' => 'EMAIL',
        'user_id' => 2,
        'token' => '85ce3890-2c2c-11e7-ad60-a330f1f9660a',
        'used_ip' => '127.0.0.1',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
    }
}
