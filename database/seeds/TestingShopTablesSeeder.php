<?php

use Illuminate\Database\Seeder;

class TestingShopTablesSeeder extends Seeder
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
        'role_id' => 1
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

      DB::table('shop_items')->truncate();
      DB::table('shop_items')->insert([
        'name' => 'TestItem',
        'description' => '',
        'category_id' => 1,
        'price' => 100,
        'displayed' => 1,
        'image_path' => NULL,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      DB::table('shop_categories')->truncate();
      DB::table('shop_categories')->insert([
        'name' => 'TestCategory',
        'order' => 1,
        'displayed' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);

    }
}
