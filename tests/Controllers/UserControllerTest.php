<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Mail\UserSignup;
use Illuminate\Support\Facades\Mail;

class UserControllerTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \Artisan::call('db:seed', ['--class' => 'TestingUsersTablesSeeder']);
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
   * Test forgot password
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
    $token = \App\UsersToken::where('user_id', 1)->where('type', 'PASSWORD')->first();
    $this->assertEquals(true, !empty($token));
    $this->assertEquals(null, $token->used_ip);
    // check email
    Mail::assertSent(UserForgotPassword::class, function ($mail) {
      return ($mail->user->id === 1 && $mail->url === url('/user/password/reset/' . $token->$token));
    });
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
