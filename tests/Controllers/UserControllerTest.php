<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Mail\UserForgotPassword;
use App\Mail\UserSignup;
use Illuminate\Support\Facades\Mail;

class UserControllerTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \Artisan::call('db:seed', ['--class' => 'TestingUsersTablesSeeder']);
    \Artisan::call('db:seed', ['--class' => 'PermissionsTablesSeeder']);
  }

  /**
   * Test authentification
   *
   * @return void
   */
  public function testLoginPage()
  {
    $response = $this->get('/login');
    $response->assertStatus(200);
  }
  public function testLoginWithoutUsername()
  {
    $response = $this->call('POST', '/login');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testLoginWithoutPassword()
  {
    $response = $this->call('POST', '/login', ['username' => 'Test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testLoginWithBlockedIP()
  {
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'test'], [], [], ['REMOTE_ADDR' => '127.0.0.2']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.login.error.blocked'))), $response->getContent());
  }
  public function testLoginWithBadUsername()
  {
    $response = $this->call('POST', '/login', ['username' => 'Teste', 'password' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.login.error.notfound'))), $response->getContent());
  }
  public function testLoginWithBadCredentials()
  {
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.login.error.credentials'))), $response->getContent());
  }
  public function testLoginWithBadCredentialsMoreThanTenTimesForBlockIP()
  {
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'teste']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.login.error.blocked'))), $response->getContent());
  }
  public function testLogin()
  {
    $response = $this->call('POST', '/login', ['username' => 'Test', 'password' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.login.success'))), $response->getContent());
    $log = \App\UsersConnectionLog::where('user_id', 1)->first();
    $this->assertEquals('127.0.0.1', $log->ip);
    $logged = $this->get('/logged');
    $this->assertEquals(json_encode(array('logged' => true)), $logged->getContent());
  }
  public function testLoginWithTwoFactorAuthDisabled()
  {
    $response = $this->call('POST', '/login', ['username' => 'Test3', 'password' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.login.success'))), $response->getContent());
    $log = \App\UsersConnectionLog::where('user_id', 3)->first();
    $this->assertEquals('127.0.0.1', $log->ip);
    $logged = $this->get('/logged');
    $this->assertEquals(json_encode(array('logged' => true)), $logged->getContent());
  }
  public function testLoginWithTwoFactorAuthEnabled()
  {
    $response = $this->call('POST', '/login', ['username' => 'Test2', 'password' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'twoFactorAuth' => true, 'success' => '')), $response->getContent());
    $log = \App\UsersConnectionLog::where('user_id', 2)->first();
    $this->assertEquals(null, $log);
    $logged = $this->get('/logged');
    $this->assertEquals(json_encode(array('logged' => false)), $logged->getContent());
  }
  public function testValidLoginWithoutSession()
  {
    $response = $this->call('POST', '/login/two-factor-auth', ['code' => 'invalid']);
    $response->assertStatus(403);
  }
  public function testLoginWithoutCode()
  {
    $response = $this->call('POST', '/login/two-factor-auth');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testValidLoginWithInvalidCode()
  {
    $this->session(['twoFactorAuth' => ['user_id' => 2, 'remember_me' => false]]);
    $response = $this->call('POST', '/login/two-factor-auth', ['code' => 'invalid']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.login.error.two_factor_auth'))), $response->getContent());
    $log = \App\UsersConnectionLog::where('user_id', 2)->first();
    $this->assertEquals(null, $log);
    $logged = $this->get('/logged');
    $this->assertEquals(json_encode(array('logged' => false)), $logged->getContent());
  }

  /**
   * Test forgot/reset password
   *
   * @return void
   */
  public function testForgotPasswordWithoutEmail()
  {
    $response = $this->call('POST', '/user/password/forgot');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testForgotPasswordWithInvalidEmail()
  {
    $response = $this->call('POST', '/user/password/forgot', ['email' => 'email']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.password.forgot.user.notfound'))), $response->getContent());
  }
  public function testForgotPassword()
  {
    Mail::fake();
    $response = $this->call('POST', '/user/password/forgot', ['email' => 'test@test.com']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.password.forgot.success'))), $response->getContent());
    // check token
    $token = \App\UsersToken::where('user_id', 1)->where('type', 'PASSWORD')->orderBy('id', 'desc')->first();
    $this->assertEquals(true, !empty($token));
    $this->assertEquals(null, $token->used_ip);
    // check email
    Mail::assertSent(UserForgotPassword::class, function ($mail) use ($token) {
      return ($mail->user->id === 1 && $mail->url === url('/user/password/reset/' . $token->token));
    });
  }
  public function testResetPasswordPageWithoutValidToken()
  {
    $response = $this->get('/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d3');
    $response->assertStatus(404);
  }
  public function testResetPasswordPageWithExpiredToken()
  {
    $response = $this->get('/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d4');
    $response->assertStatus(404);
  }
  public function testResetPasswordPageWithUsedToken()
  {
    $response = $this->get('/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d3');
    $response->assertStatus(404);
  }
  public function testResetPasswordPage()
  {
    $response = $this->get('/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d5');
    $response->assertStatus(200);
  }
  public function testResetPasswordWithoutValidToken()
  {
    $response = $this->call('POST', '/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d0', ['password' => 'pass', 'password_confirmation' => 'pass']);
    $response->assertStatus(404);
  }
  public function testResetPasswordWithUsedToken()
  {
    $response = $this->call('POST', '/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d4', ['password' => 'pass', 'password_confirmation' => 'pass']);
    $response->assertStatus(404);
  }
  public function testResetPasswordWithExpiredToken()
  {
    $response = $this->call('POST', '/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d3', ['password' => 'pass', 'password_confirmation' => 'pass']);
    $response->assertStatus(404);
  }
  public function testResetPasswordWithoutPassword()
  {
    $response = $this->call('POST', '/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d5');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testResetPasswordWithoutConfirmationPassword()
  {
    $response = $this->call('POST', '/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d5', ['password' => 'pass']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testResetPasswordWithPasswordNotEqualToConfirmation()
  {
    $response = $this->call('POST', '/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d5', ['password' => 'pass', 'password_confirmation' => 'yolo']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.passwords'))), $response->getContent());
  }
  public function testResetPassword()
  {
    $response = $this->call('POST', '/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d5', ['password' => 'pass', 'password_confirmation' => 'pass']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.password.reset.success'), 'redirect' => url('/user'))), $response->getContent());
    // check if token is used
    $token = \App\UsersToken::where('token', 'c0fb81a0-2d90-11e7-ba3f-0923098860d5')->first();
    $this->assertEquals('127.0.0.1', $token->used_ip);
    // check password
    $user = \App\User::where('id', $token->user_id)->first();
    $this->assertEquals(\App\User::hash('pass', $user->username), $user->password);
    // is logged
    $logged = $this->get('/logged');
    $this->assertEquals(json_encode(array('logged' => true)), $logged->getContent());
  }

  /**
   * Test edit password
   *
   * @return void
   */
  public function testEditPasswordNotLogged()
  {
    $response = $this->call('POST', '/user/password', ['password' => 'pass', 'password_confirmation' => 'pass']);
    $response->assertStatus(302);
  }
  public function testEditPasswordWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('POST', '/user/password', ['password' => 'pass', 'password_confirmation' => 'pass']);
    $response->assertStatus(403);
  }
  public function testEditPasswordWithoutPassword()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/password', []);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testEditPasswordWithoutPasswordConfirmation()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/password', ['password' => 'pass']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testEditPasswordWithPasswordNotEqualToConfirmation()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/password', ['password' => 'pass', 'password_confirmation' => 'yolo']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.passwords'))), $response->getContent());
  }
  public function testEditPassword()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/password', ['password' => 'pass', 'password_confirmation' => 'pass']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.password.edit.success'))), $response->getContent());
    // check password
    $user = \App\User::find(1);
    $this->assertEquals(\App\User::hash('pass', $user->username), $user->password);
  }

  /**
   * Test request new email
   *
   * @return void
   */
  public function testRequestEditEmailNotLogged()
  {
    $response = $this->call('POST', '/user/email', ['email' => 'new@email.com', 'reason' => 'why']);
    $response->assertStatus(302);
  }
  public function testRequestEditEmailWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('POST', '/user/email', ['email' => 'new@email.com', 'reason' => 'why']);
    $response->assertStatus(403);
  }
  public function testRequestEditEmailWithoutEmail()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/email', []);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testRequestEditEmailWithoutReason()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/email', ['email' => 'email@email.com']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testRequestEditEmailWithInvalidEmail()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/email', ['email' => 'invalid', 'reason' => 'why']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.email'))), $response->getContent());
  }
  public function testRequestEditEmailWithEmailAlreadyTaken()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/email', ['email' => 'test2@test.com', 'reason' => 'why']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.email.taken'))), $response->getContent());
  }
  public function testRequestEditEmailWhenAlreadyRequested()
  {
    $user = \App\User::find(2);
    $this->be($user);

    $response = $this->call('POST', '/user/email', ['email' => 'test5@test.com', 'reason' => 'why']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.email.edit.request.already'))), $response->getContent());
  }
  public function testRequestEditEmail()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/email', ['email' => 'test5@test.com', 'reason' => 'why']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.email.edit.request.success'))), $response->getContent());
    // check if database
    $request = \App\UsersEmailEditRequest::where('user_id', 1)->first();
    $this->assertEquals(false, empty($request));
    $this->assertEquals('test5@test.com', $request->email);
    $this->assertEquals('why', $request->reason);
    $this->assertEquals('127.0.0.1', $request->ip);
  }

  /**
   * Test edit username
   *
   * @return void
   */
  public function testEditUsernameNotLogged()
  {
    $response = $this->call('POST', '/user/username', ['username' => 'test_edit']);
    $response->assertStatus(302);
  }
  public function testEditUsernameWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('POST', '/user/username', ['username' => 'test_edit']);
    $response->assertStatus(403);
  }
  public function testEditUsernameWithoutPurchaseIt()
  {
    $user = \App\User::find(2);
    $this->be($user);

    $response = $this->call('POST', '/user/username', ['username' => 'test_edit']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.profile.edit.username.error.purchase'))), $response->getContent());
  }
  public function testEditUsernameAlreadyEditedLastTwoWeeks()
  {
    // add to db
    $log = new \App\UsersEditUsernameHistory();
    $log->user_id = 1;
    $log->old_username = 'Tester';
    $log->new_username = 'Test';
    $log->ip = '127.0.0.1';
    $log->save();

    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/username', ['username' => 'test_edit']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.profile.edit.username.error.two_weeks'))), $response->getContent());
  }
  public function testEditUsernameAlreadyEditedTwoTimes()
  {
    // add to db
    $log = new \App\UsersEditUsernameHistory();
    $log->user_id = 1;
    $log->old_username = 'Tester';
    $log->new_username = 'Test';
    $log->ip = '127.0.0.1';
    $log->save();
    $log = new \App\UsersEditUsernameHistory();
    $log->user_id = 1;
    $log->old_username = 'Test';
    $log->new_username = 'Tester';
    $log->ip = '127.0.0.1';
    $log->save();

    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/username', ['username' => 'test_edit']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.profile.edit.username.error.two_times'))), $response->getContent());
  }
  public function testEditUsernameWithoutUsername()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/username', []);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testEditUsernameWithoutPassword()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/username', ['username' => 'test_edit']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testEditUsernameWithInvalidPassword()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/username', ['username' => 'test_edit', 'password' => 'invalid']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.profile.edit.username.error.password'))), $response->getContent());
  }
  public function testEditUsernameWithInvalidUsername()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/username', ['username' => '@fsdijfs', 'password' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.username'))), $response->getContent());
  }
  public function testEditUsernameWithAlreadyTakenUsername()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/username', ['username' => 'Test2', 'password' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.username.taken'))), $response->getContent());
  }
  public function testEditUsername()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('POST', '/user/username', ['username' => 'test_edit', 'password' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.profile.edit.username.success'))), $response->getContent());
    // check data
    $user = \App\User::find(1);
    $this->assertEquals('test_edit', $user->username);
    // check password
    $this->assertEquals(\App\User::hash('test', $user->username), $user->password);
    // check log
    $log = \App\UsersEditUsernameHistory::where('user_id' , 1)->where('old_username', 'Test')->where('new_username', 'test_edit')->where('ip', '127.0.0.1')->first();
    $this->assertEquals(1, count($log));
    // check ability
    $ability = \App\UsersEditUsernameAbility::where('user_id' , 1)->first();
    $this->assertEquals($log->id, $ability->history_id);
  }

  /**
   * Test transfer money
   *
   * @return void
   */
  public function testTransferMoneyNotLogged()
  {
    $response = $this->call('PUT', '/user/money', ['amount' => 10, 'to' => 'Test2']);
    $response->assertStatus(302);
  }
  public function testTransferMoneyWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('PUT', '/user/money', ['amount' => 10, 'to' => 'Test2']);
    $response->assertStatus(403);
  }
  public function testTransferWithoutAmount()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('PUT', '/user/money', []);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testTransferMoneyWihoutTo()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('PUT', '/user/money', ['amount' => 10]);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testTransferMoneyInvalidAmount()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('PUT', '/user/money', ['amount' => 'sdgsd', 'to' => 'Test2']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.profile.transfer.money.error.amount'))), $response->getContent());
  }
  public function testTransferMoneyWithoutMoney()
  {
    $user = \App\User::find(2);
    $this->be($user);

    $response = $this->call('PUT', '/user/money', ['amount' => 10, 'to' => 'Test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.profile.transfer.money.error.no_enough'))), $response->getContent());
  }
  public function testTransferMoneyToAnUnknownUser()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('PUT', '/user/money', ['amount' => 10, 'to' => 'invalid']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.profile.transfer.money.error.unknown_user'))), $response->getContent());
  }
  public function testTransferMoneyToHimself()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('PUT', '/user/money', ['amount' => 10, 'to' => 'Test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.profile.transfer.money.error.himself'))), $response->getContent());
  }
  public function testTransferMoneyWithoutEnoughMoney()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('PUT', '/user/money', ['amount' => 1000, 'to' => 'Test2']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.profile.transfer.money.error.no_enough'))), $response->getContent());
  }
  public function testTransferMoney()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('PUT', '/user/money', ['amount' => 10, 'to' => 'Test2']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.profile.transfer.money.success', ['money' => 10, 'username' => 'Test2']), 'money' => 0)), $response->getContent());
    // check users money
    $user = \App\User::find(1);
    $this->assertEquals(0, $user->money);
    $user = \App\User::find(2);
    $this->assertEquals(10, $user->money);
    // check history
    $history = \App\UsersTransferMoneyHistory::get();
    $this->assertEquals(1, count($history));
    $this->assertEquals(1, $history[0]->user_id);
    $this->assertEquals(2, $history[0]->to);
    $this->assertEquals(10, $history[0]->amount);
    $this->assertEquals('127.0.0.1', $history[0]->ip);
  }

  /**
   * Test sign up
   *
   * @return void
   */
  public function testSignupPage()
  {
    $response = $this->get('/signup');
    $response->assertStatus(200);
  }
  public function testSignupWithoutUsername()
  {
    $response = $this->call('POST', '/signup');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testSignupWithoutPassword()
  {
    $response = $this->call('POST', '/signup', ['username' => 'Test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testSignupWithoutPasswordConfirmation()
  {
    $response = $this->call('POST', '/signup', ['username' => 'Test', 'password' => 'password']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testSignupWithPasswordNotEqualToConfirmation()
  {
    $response = $this->call('POST', '/user/password/reset/c0fb81a0-2d90-11e7-ba3f-0923098860d3', ['username' => 'Test', 'password' => 'pass', 'password_confirmation' => 'yolo']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.passwords'))), $response->getContent());
  }
  public function testSignupWithoutEmail()
  {
    $response = $this->call('POST', '/signup', ['username' => 'Test', 'password' => 'password', 'password_confirmation' => 'password']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testSignupWithoutLegal()
  {
    $response = $this->call('POST', '/signup', ['username' => 'Test', 'password' => 'password', 'password_confirmation' => 'password', 'email' => 'email']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.legal'))), $response->getContent());
  }
  public function testSignupWithInvalidCaptcha()
  {
    $response = $this->call('POST', '/signup', ['username' => 'Test', 'password' => 'password', 'password_confirmation' => 'password', 'email' => 'email', 'legal' => 'on', 'g-recaptcha-response' => 'invalid']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.captcha'))), $response->getContent());
  }
  public function testSignupWithInvalidUsernameUnderTwoChar()
  {
    $response = $this->call('POST', '/signup', ['username' => 'u', 'password' => 'password', 'password_confirmation' => 'password', 'email' => 'email', 'legal' => 'on', 'g-recaptcha-response' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.username'))), $response->getContent());
  }
  public function testSignupWithInvalidUsernameAboveSixteenChar()
  {
    $response = $this->call('POST', '/signup', ['username' => '12345678912345678', 'password' => 'password', 'password_confirmation' => 'password', 'email' => 'email', 'legal' => 'on', 'g-recaptcha-response' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.username'))), $response->getContent());
  }
  public function testSignupWithInvalidUsernameSpecialChars()
  {
    $response = $this->call('POST', '/signup', ['username' => 'Test@', 'password' => 'password', 'password_confirmation' => 'password', 'email' => 'email', 'legal' => 'on', 'g-recaptcha-response' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.username'))), $response->getContent());
  }
  public function testSignupWithInvalidEmail()
  {
    $response = $this->call('POST', '/signup', ['username' => 'Test', 'password' => 'password', 'password_confirmation' => 'password', 'email' => 'email', 'legal' => 'on', 'g-recaptcha-response' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.email'))), $response->getContent());
  }
  public function testSignupWithInvalidPasswords()
  {
    $response = $this->call('POST', '/signup', ['username' => 'Test', 'password' => 'password', 'password_confirmation' => 'password2', 'email' => 'email@email.com', 'legal' => 'on', 'g-recaptcha-response' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.passwords'))), $response->getContent());
  }
  public function testSignupWithAlreadyTakenUsername()
  {
    $response = $this->call('POST', '/signup', ['username' => 'Test', 'password' => 'password', 'password_confirmation' => 'password', 'email' => 'email@email.com', 'legal' => 'on', 'g-recaptcha-response' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.username.taken'))), $response->getContent());
  }
  public function testSignupWithAlreadyTakenEmail()
  {
    $response = $this->call('POST', '/signup', ['username' => 'Test4', 'password' => 'password', 'password_confirmation' => 'password', 'email' => 'test@test.com', 'legal' => 'on', 'g-recaptcha-response' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('user.signup.error.email.taken'))), $response->getContent());
  }
  public function testSignup()
  {
    Mail::fake();
    $response = $this->call('POST', '/signup', ['username' => 'Test4', 'password' => 'password', 'password_confirmation' => 'password', 'email' => 'test4@test.com', 'legal' => 'on', 'g-recaptcha-response' => 'test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('user.signup.success'), 'redirect' => url('/user'))), $response->getContent());
    // find user
    $user = \App\User::find(4);
    $this->assertEquals(true, !empty($user));
    $this->assertEquals('Test4', $user->username);
    $this->assertEquals('127.0.0.1', $user->ip);
    // check token
    $token = \App\UsersToken::where('user_id', 4)->first();
    $this->assertEquals(true, !empty($token));
    $this->assertEquals(null, $token->used_ip);
    // check email
    Mail::assertSent(UserSignup::class, function ($mail) {
      return $mail->user->id === 4;
    });
    // check logged
    $logged = $this->get('/logged');
    $this->assertEquals(json_encode(array('logged' => true)), $logged->getContent());
  }

  /**
   * Test confirm mail
   *
   * @return void
  */
  public function testConfirmMailWithoutToken()
  {
    $response = $this->get('/user/email/confirm/');
    $response->assertStatus(404);
  }
  public function testConfirmMailWithInvalidToken()
  {
    $response = $this->get('/user/email/confirm/invalid');
    $response->assertStatus(404);
  }
  public function testConfirmMailWithUsedToken()
  {
    $response = $this->get('/user/email/confirm/85ce3890-2c2c-11e7-ad60-a330f1f9660a');
    $response->assertStatus(404);
  }
  public function testConfirmMail()
  {
    $response = $this->call('GET', '/user/email/confirm/85ce3890-2c2c-11e7-ad60-a330f1f9660b');
    $response->assertStatus(302);
    // check if token is used
    $token = \App\UsersToken::where('token', '85ce3890-2c2c-11e7-ad60-a330f1f9660b')->first();
    $this->assertEquals('127.0.0.1', $token->used_ip);
  }

  /**
   * Test send confirm mail
   *
   * @return void
   */
  public function testSendConfirmMailUnlogged()
  {
    $response = $this->call('GET', '/user/email/send');
    $response->assertStatus(302);
  }
  public function testSendConfirmMailWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('GET', '/user/email/send');
    $response->assertStatus(403);
  }
  public function testSendConfirmMailWithoutValidToken()
  {
    $user = \App\User::find(2);
    $this->be($user);
    $response = $this->call('GET', '/user/email/send');
    $response->assertStatus(404);
  }
  public function testSendConfirmMail()
  {
    $user = \App\User::find(1);
    $this->be($user);

    Mail::fake();

    $response = $this->call('GET', '/user/email/send');
    $response->assertStatus(302);

    Mail::assertSent(UserSignup::class, function ($mail) {
      return $mail->user->id === 1;
    });
  }
}
