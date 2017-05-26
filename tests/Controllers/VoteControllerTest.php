<?php
namespace Tests\Feature;

class GuzzleResponse {
  public function getStatusCode()
  {
    return 200;
  }
  public function getBody()
  {
    return '<br /><b>Position 14</b><br><br>Clic Sortant : 10</td></tr>';
  }
}

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Mail;

class VoteControllerTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \Artisan::call('db:seed', ['--class' => 'TestingVoteTablesSeeder']);
    \Artisan::call('db:seed', ['--class' => 'PermissionsTablesSeeder']);
  }

  public function testLoadPage()
  {
    $response = $this->get('/vote');
    $response->assertStatus(200);
  }

  public function testStepOneWithoutUsername()
  {
    $response = $this->call('POST', '/vote/step/one');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testStepOneWithUnknownUser()
  {
    $response = $this->call('POST', '/vote/step/one', ['username' => 'Testeee']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('vote.step.one.error.user'))), $response->getContent());
  }
  public function testStepOne()
  {
    $response = $this->call('POST', '/vote/step/one', ['username' => 'Test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('vote.step.one.success'))), $response->getContent());
    $response->assertSessionHas('vote.user.id', 1);
  }

  public function testStepThreeWithoutBeAuth()
  {
    $response = $this->call('POST', '/vote/step/three', ['out' => '10']);
    $response->assertStatus(403);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('vote.step.error.unauthorized'))), $response->getContent());
  }
  public function testStepThreeWithoutOut()
  {
    $response = $this->withSession(['vote.user.id' => 1])->call('POST', '/vote/step/three');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testStepThreeWithoutValidOut()
  {
    $response = $this->withSession(['vote.user.id' => 1])->call('POST', '/vote/step/three', ['out' => 'invalid']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('vote.step.three.error.out'))), $response->getContent());
  }
  public function testStepThree()
  {
    $guzzleClient = $this->getMockBuilder(\GuzzleHttp\Client::class)
                         ->setMethods(['get'])
                         ->getMock();
    $guzzleClient->expects($this->once())
        ->method('get')
        ->willReturn(new GuzzleResponse());
    $this->app->instance('\GuzzleHttp\Client', $guzzleClient);

    $response = $this->withSession(['vote.user.id' => 1])->call('POST', '/vote/step/three', ['out' => '10']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('vote.step.three.success'))), $response->getContent());
    $response->assertSessionHas('vote.user.id', 1);
    $response->assertSessionHas('vote.valid', true);
  }
}
