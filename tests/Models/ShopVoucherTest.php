<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\ShopVoucher;
// TODO
class ShopVoucherTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
  }
}
