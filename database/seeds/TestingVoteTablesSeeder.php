<?php

use Illuminate\Database\Seeder;

class TestingVoteTablesSeeder extends Seeder
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

      DB::table('votes')->truncate();
      DB::table('votes')->insert([
        'user_id' => 2,
        'out' => 10,
        'reward_id' => 1,
        'reward_getted' => 0,
        'money_earned' => 0,
        'ip' => '127.0.0.2',
        'created_at' => date('Y-m-d H:i:s', strtotime('- '.(env('VOTE_TIME')+1).' minutes')),
        'updated_at' => date('Y-m-d H:i:s', strtotime('- '.(env('VOTE_TIME')+1).' minutes'))
      ]);
      DB::table('votes')->insert([
        'user_id' => 3,
        'out' => 10,
        'reward_id' => 1,
        'reward_getted' => 0,
        'money_earned' => 0,
        'ip' => '127.0.0.2',
        'created_at' => date('Y-m-13 12:00:00', strtotime('- 1 month')),
        'updated_at' => date('Y-m-13 12:00:00', strtotime('- 1 month'))
      ]);
      DB::table('vote_rewards')->truncate();
      for ($i=1; $i < 5; $i++) {
        DB::table('vote_rewards')->insert([
          'name' => "Reward#$i",
          'probability' => 25,
          'commands' => '',
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s')
        ]);
      }

      DB::table('notifications')->truncate();
      DB::table('notifications')->insert([
        'user_id' => 2,
        'type' => 'info',
        'key' => 'vote.reset.kit.get',
        'vars' => '{"url":"http:\/\/localhost\/vote\/reward\/kit\/get","position":1}',
        'seen' => 0,
        'auto_seen' => 0,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      DB::table('vote_user_kits')->truncate();
      DB::table('vote_user_kits')->insert([
        'user_id' => 2,
        'kit_id' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
    }
}
