<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Mail;

class ObsiguardControllerTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \Artisan::call('db:seed', ['--class' => 'TestingObsiguardTablesSeeder']);
    \Artisan::call('db:seed', ['--class' => 'PermissionsTablesSeeder']);
  }

  public function testEnableUnlogged()
  {
    $response = $this->call('GET', '/user/obsiguard/enable');
    $response->assertStatus(302);
  }
  public function testEnableWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('GET', '/user/obsiguard/enable');
    $response->assertStatus(403);
  }
  public function testEnable()
  {
    $user = \App\User::find(2);
    $this->be($user);

    $response = $this->call('GET', '/user/obsiguard/enable');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.obsiguard.enable.success'), 'data' => ['ip' => '127.0.0.1', 'id' => 3])), $response->getContent());
    // check db
    $ip = \App\UsersObsiguardIP::where('user_id', 2)->first();
    $this->assertEquals(1, count($ip));
    $this->assertEquals('127.0.0.1', $ip->ip);
    $token = \App\UsersToken::where('user_id', 2)->where('used_ip', '127.0.0.1')->first();
    $this->assertEquals(1, count($token));
    $response->assertSessionHas('user.obsiguard.security.code', $token->token);
    // check log
    $log = \App\UsersObsiguardLog::where('user_id', 2)->where('type', 'ENABLE')->where('ip', '127.0.0.1')->where('data', NULL)->get();
    $this->assertEquals(1, count($log));
  }

  public function testValidSecurityCodeUnlogged()
  {
    $response = $this->call('GET', '/user/obsiguard/enable');
    $response->assertStatus(302);
  }
  public function testValidSecurityCodeWithoutCode()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/obsiguard/security/valid', []);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testValidSecurityCodeWithInvalidCode()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/obsiguard/security/valid', ['code' => 'invalid']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.obsiguard.security.error'))), $response->getContent());
  }
  public function testValidSecurityCodeWithAlreadyUsedCode()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $code = str_random(5);
    \App\UsersToken::generate('OBSIGUARD', 2, 'token-used');
    $token = \App\UsersToken::getToken();
    $token->used_ip = '127.0.0.1';
    $token->save();

    $response = $this->call('POST', '/user/obsiguard/security/valid', ['code' => 'token-used']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.obsiguard.security.error'))), $response->getContent());
  }
  public function testValidSecurityCode()
  {
    $code = str_random(5);
    $token = \App\UsersToken::generate('OBSIGUARD', 2, $code);

    $user = \App\User::find(2);
    $this->be($user);

    $response = $this->call('POST', '/user/obsiguard/security/valid', ['code' => $code]);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => '')), $response->getContent());
    $token = \App\UsersToken::where('user_id', 2)->where('data', $code)->where('used_ip', '127.0.0.1')->first();
    $this->assertEquals(1, count($token));
    $response->assertSessionHas('user.obsiguard.security.code', $token->token);
  }

  public function testDisableUnlogged()
  {
    $response = $this->call('GET', '/user/obsiguard/disable');
    $response->assertStatus(302);
  }
  public function testDisableWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('GET', '/user/obsiguard/disable');
    $response->assertStatus(403);
  }
  public function testDisableWithoutSecurityCode()
  {
    $user = \App\User::find(2);
    $this->be($user);

    Mail::fake();
    $response = $this->call('GET', '/user/obsiguard/disable');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => '', 'obsiguard' => false)), $response->getContent());

    $token = \App\UsersToken::where('user_id', 2)->where('type', 'OBSIGUARD')->first();
    $this->assertEquals(1, count($token));

    Mail::assertSent(\App\Mail\ObsiguardToken::class, function ($mail) use ($token) {
      return $mail->user->id === 2 && $mail->code === $token->data;
    });
  }
  public function testDisable()
  {
    $user = \App\User::find(1);
    $this->be($user);
    $this->withSession(['user.obsiguard.security.code' => 'c0fb81a0-2d90-11e7-ba3f-0923098860d5']);

    $response = $this->call('GET', '/user/obsiguard/disable');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.obsiguard.disable.success'))), $response->getContent());

    $ip = \App\UsersObsiguardIP::where('user_id', 1)->get();
    $this->assertEquals(0, count($ip));
    // check log
    $log = \App\UsersObsiguardLog::where('user_id', 1)->where('type', 'DISABLE')->where('ip', '127.0.0.1')->where('data', NULL)->get();
    $this->assertEquals(1, count($log));
  }

  public function testAddIPUnlogged()
  {
    $response = $this->call('POST', '/user/obsiguard/ip');
    $response->assertStatus(302);
  }
  public function testAddIPWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('POST', '/user/obsiguard/ip');
    $response->assertStatus(403);
  }
  public function testAddIPWithoutSecurityCode()
  {
    $user = \App\User::find(2);
    $this->be($user);

    Mail::fake();
    $response = $this->call('POST', '/user/obsiguard/ip', ['ip' => '127.0.0.3']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => '', 'obsiguard' => false)), $response->getContent());

    $token = \App\UsersToken::where('user_id', 2)->where('type', 'OBSIGUARD')->first();
    $this->assertEquals(1, count($token));

    Mail::assertSent(\App\Mail\ObsiguardToken::class, function ($mail) use ($token) {
      return $mail->user->id === 2 && $mail->code === $token->data;
    });
  }
  public function testAddIPWithoutIP()
  {
    $user = \App\User::find(1);
    $this->be($user);
    $this->withSession(['user.obsiguard.security.code' => 'c0fb81a0-2d90-11e7-ba3f-0923098860d5']);

    $response = $this->call('POST', '/user/obsiguard/ip', []);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testAddIPWithAnInvalidIP()
  {
    $user = \App\User::find(1);
    $this->be($user);
    $this->withSession(['user.obsiguard.security.code' => 'c0fb81a0-2d90-11e7-ba3f-0923098860d5']);

    $response = $this->call('POST', '/user/obsiguard/ip', ['ip' => 'invalid']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.obsiguard.add.error'))), $response->getContent());
  }
  public function testAddIP()
  {
    $user = \App\User::find(1);
    $this->be($user);
    $this->withSession(['user.obsiguard.security.code' => 'c0fb81a0-2d90-11e7-ba3f-0923098860d5']);

    $response = $this->call('POST', '/user/obsiguard/ip', ['ip' => '127.0.0.3']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.obsiguard.add.success'), 'data' => ['id' => 3, 'ip' => '127.0.0.3'])), $response->getContent());

    $ip = \App\UsersObsiguardIP::where('user_id', 1)->get();
    $this->assertEquals(3, count($ip));
    $this->assertEquals('127.0.0.3', $ip[2]->ip);
    // check log
    $log = \App\UsersObsiguardLog::where('user_id', 1)->where('type', 'ADD')->where('ip', '127.0.0.1')->where('data', '127.0.0.3')->get();
    $this->assertEquals(1, count($log));
  }

  public function testRemoveIPUnlogged()
  {
    $response = $this->call('DELETE', '/user/obsiguard/ip/1');
    $response->assertStatus(302);
  }
  public function testRemoveIPWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('DELETE', '/user/obsiguard/ip/1');
    $response->assertStatus(403);
  }
  public function testRemoveIPWithoutSecurityCode()
  {
    $user = \App\User::find(2);
    $this->be($user);

    Mail::fake();
    $response = $this->call('DELETE', '/user/obsiguard/ip/1');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => '', 'obsiguard' => false)), $response->getContent());

    $token = \App\UsersToken::where('user_id', 2)->where('type', 'OBSIGUARD')->first();
    $this->assertEquals(1, count($token));

    Mail::assertSent(\App\Mail\ObsiguardToken::class, function ($mail) use ($token) {
      return $mail->user->id === 2 && $mail->code === $token->data;
    });
  }
  public function testRemoveIPWithoutIPId()
  {
    $user = \App\User::find(1);
    $this->be($user);
    $this->withSession(['user.obsiguard.security.code' => 'c0fb81a0-2d90-11e7-ba3f-0923098860d5']);

    $response = $this->call('DELETE', '/user/obsiguard/ip/');
    $response->assertStatus(405);
  }
  public function testRemoveIPWithInvalidIPId()
  {
    $user = \App\User::find(1);
    $this->be($user);
    $this->withSession(['user.obsiguard.security.code' => 'c0fb81a0-2d90-11e7-ba3f-0923098860d5']);

    $response = $this->call('DELETE', '/user/obsiguard/ip/10');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => '')), $response->getContent());

    $ip = \App\UsersObsiguardIP::where('user_id', 1)->get();
    $this->assertEquals(2, count($ip));
    $this->assertEquals('127.0.0.1', $ip[0]->ip);
    $this->assertEquals('127.0.0.2', $ip[1]->ip);
    // check log
    $log = \App\UsersObsiguardLog::where('user_id', 1)->where('type', 'ADD')->where('ip', '127.0.0.1')->get();
    $this->assertEquals(0, count($log));
  }
  public function testRemoveIP()
  {
    $user = \App\User::find(1);
    $this->be($user);
    $this->withSession(['user.obsiguard.security.code' => 'c0fb81a0-2d90-11e7-ba3f-0923098860d5']);

    $response = $this->call('DELETE', '/user/obsiguard/ip/1');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => '')), $response->getContent());

    $ip = \App\UsersObsiguardIP::where('user_id', 1)->get();
    $this->assertEquals(1, count($ip));
    $this->assertEquals('127.0.0.2', $ip[0]->ip);
    // check log
    $log = \App\UsersObsiguardLog::where('user_id', 1)->where('type', 'REMOVE')->where('ip', '127.0.0.1')->where('data', '127.0.0.1')->get();
    $this->assertEquals(1, count($log));
  }

  public function testEnableDynamicIPUnlogged()
  {
    $response = $this->call('GET', '/user/obsiguard/ip/dynamic/enable');
    $response->assertStatus(302);
  }
  public function testEnableDynamicIPWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('GET', '/user/obsiguard/ip/dynamic/enable');
    $response->assertStatus(403);
  }
  public function testEnableDynamicIP()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('GET', '/user/obsiguard/ip/dynamic/enable');
    $response->assertStatus(200);
    $user = \App\User::find(1);
    $this->assertEquals(1, $user->obsiguard_dynamic);
  }

  public function testDisableDynamicIPUnlogged()
  {
    $response = $this->call('GET', '/user/obsiguard/ip/dynamic/disable');
    $response->assertStatus(302);
  }
  public function testDisableDynamicIPWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('GET', '/user/obsiguard/ip/dynamic/disable');
    $response->assertStatus(403);
  }
  public function testDisableDynamicIP()
  {
    $user = \App\User::find(1);
    $user->obsiguard_dynamic = true;
    $user->save();
    $this->be($user);

    $response = $this->call('GET', '/user/obsiguard/ip/dynamic/disable');
    $response->assertStatus(200);
    $user = \App\User::find(1);
    $this->assertEquals(0, $user->obsiguard_dynamic);
  }
}
