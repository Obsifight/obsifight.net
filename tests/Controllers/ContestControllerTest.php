<?php
namespace Tests\Feature;

use App\Contest;
use App\ContestsComment;
use App\ContestsHistory;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ContestControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        \Artisan::call('db:seed', ['--class' => 'PermissionsTablesSeeder']);
        \Artisan::call('db:seed', ['--class' => 'TestingContestTablesSeeder']);
    }

    public function testIndexNotLogged()
    {
        $response = $this->get('/sanctions/contest');
        $response->assertStatus(200);
        $data = Contest::orderBy('id', 'desc')->limit(5)->get();
        $data[0]->user->username;
        $this->assertEquals(
            $data->toArray(),
            $response->getOriginalContent()->getData()['contests']->toArray()
        );
    }

    public function testIndexLogged()
    {
        $user = \App\User::find(1);
        $this->be($user);

        if (!class_exists('ApiObsifight'))
            require base_path('vendor/eywek/obsifight/API/ApiObsifight.class.php');
        $api = $this->getMockBuilder(\ApiObsifight::class)
            ->setMethods(['get'])
            ->setConstructorArgs([env('API_OBSIFIGHT_USER'), env('API_OBSIFIGHT_PASS')])
            ->getMock();
        $api->expects($this->once())
            ->method('get')
            ->willReturn((object)['status' => true, 'success' => true, 'body' => [
                'bans' => [['id' => 2, 'reason' => 'Ma raison', 'date' => '2016-07-13T21:12:54.000Z'], ['id' => 1, 'reason' => 'Ma raison 2', 'date' => '2016-07-13T21:12:54.000Z']],
                'kicks' => [],
                'mutes' => []
            ]]);
        $this->app->instance('\ApiObsifight', $api);

        $response = $this->get('/sanctions/contest');
        $response->assertStatus(200);
        $body = $response->getOriginalContent()->getData();
        $data = Contest::orderBy('id', 'desc')->limit(5)->get();
        $data[0]->user->username;
        $this->assertEquals(
            $data->toArray(),
            $body['contests']->toArray()
        );
        $this->assertEquals(
            [
                (object)[
                    'id' => 2,
                    'reason' => 'Ma raison',
                    'type' => 'ban',
                    'date' => new Carbon('2016-07-13T21:12:54.000Z'),
                    'contest' => null
                ],
                (object)[
                    'id' => 1,
                    'reason' => 'Ma raison 2',
                    'type' => 'ban',
                    'date' => new Carbon('2016-07-13T21:12:54.000Z'),
                    'contest' => Contest::find(1)->first()
                ],
            ],
            $body['sanctions']
        );
    }

    public function testAddUnlogged()
    {
        $response = $this->post('/sanctions/contest', []);
        $response->assertStatus(302);
    }

    public function testAddWithoutPermission()
    {
        $user = \App\User::find(2);
        $this->be($user);
        $response = $this->post('/sanctions/contest', []);
        $response->assertStatus(403);
    }

    public function testAddWithoutFields()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/sanctions/contest', []);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'error' => __('form.error.fields')
        ]);

        $response = $this->post('/sanctions/contest', ['sanction' => 1]);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'error' => __('form.error.fields')
        ]);

        $response = $this->post('/sanctions/contest', ['sanction' => 1, 'sanction_type' => 'ban']);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'error' => __('form.error.fields')
        ]);

        $response = $this->post('/sanctions/contest', ['sanction' => 1, 'sanction_type' => 'banfake', 'reason' => 'lol']);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'error' => __('form.error.fields')
        ]);
    }

    public function testAddWithNotFoundSanction()
    {
        $user = \App\User::find(1);
        $this->be($user);

        if (!class_exists('ApiObsifight'))
            require base_path('vendor/eywek/obsifight/API/ApiObsifight.class.php');
        $api = $this->getMockBuilder(\ApiObsifight::class)
            ->setMethods(['get'])
            ->setConstructorArgs([env('API_OBSIFIGHT_USER'), env('API_OBSIFIGHT_PASS')])
            ->getMock();
        $api->expects($this->once())
            ->method('get')
            ->willReturn((object)['status' => false, 'success' => true, 'body' => []]);
        $this->app->instance('\ApiObsifight', $api);

        $response = $this->post('/sanctions/contest', ['sanction' => 1, 'sanction_type' => 'ban', 'reason' => 'lol']);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'error' => __('sanction.contest.error.api')
        ]);
    }

    public function testAddWithNotActiveSanction()
    {
        $user = \App\User::find(1);
        $this->be($user);

        if (!class_exists('ApiObsifight'))
            require base_path('vendor/eywek/obsifight/API/ApiObsifight.class.php');
        $api = $this->getMockBuilder(\ApiObsifight::class)
            ->setMethods(['get'])
            ->setConstructorArgs([env('API_OBSIFIGHT_USER'), env('API_OBSIFIGHT_PASS')])
            ->getMock();
        $api->expects($this->once())
            ->method('get')
            ->willReturn((object)['status' => true, 'success' => true, 'body' => [
                'ban' => ['state' => 0]
            ]]);
        $this->app->instance('\ApiObsifight', $api);

        $response = $this->post('/sanctions/contest', ['sanction' => 1, 'sanction_type' => 'ban', 'reason' => 'lol']);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'error' => __('sanction.contest.error.end')
        ]);
    }

    public function testAddWithAlreadyContestedSanction()
    {
        $user = \App\User::find(1);
        $this->be($user);

        if (!class_exists('ApiObsifight'))
            require base_path('vendor/eywek/obsifight/API/ApiObsifight.class.php');
        $api = $this->getMockBuilder(\ApiObsifight::class)
            ->setMethods(['get'])
            ->setConstructorArgs([env('API_OBSIFIGHT_USER'), env('API_OBSIFIGHT_PASS')])
            ->getMock();
        $api->method('get')
            ->willReturn((object)['status' => true, 'success' => true, 'body' => [
                'ban' => ['state' => 1]
            ]]);
        $this->app->instance('\ApiObsifight', $api);

        $response = $this->post('/sanctions/contest', ['sanction' => 1, 'sanction_type' => 'ban', 'reason' => 'lol']);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'error' => __('sanction.contest.error.already')
        ]);

        $contest = Contest::find(1);
        $contest->status = 'CLOSED';
        $contest->save();
        $response = $this->post('/sanctions/contest', ['sanction' => 1, 'sanction_type' => 'ban', 'reason' => 'lol']);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'error' => __('sanction.contest.error.already')
        ]);
    }

    public function testAdd()
    {
        $user = \App\User::find(1);
        $this->be($user);

        if (!class_exists('ApiObsifight'))
            require base_path('vendor/eywek/obsifight/API/ApiObsifight.class.php');
        $api = $this->getMockBuilder(\ApiObsifight::class)
            ->setMethods(['get'])
            ->setConstructorArgs([env('API_OBSIFIGHT_USER'), env('API_OBSIFIGHT_PASS')])
            ->getMock();
        $api->expects($this->once())
            ->method('get')
            ->willReturn((object)['status' => true, 'success' => true, 'body' => [
                'ban' => ['state' => 1]
            ]]);
        $this->app->instance('\ApiObsifight', $api);

        \DB::table('contests')->truncate();
        \DB::table('contests')->insert([
            'sanction_id' => 1,
            'sanction_type' => 'ban',
            'user_id' => 1, // test
            'status' => 'CLOSED',
            'reason' => 'Test',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s', strtotime('-2 months'))
        ]);

        $response = $this->post('/sanctions/contest', ['sanction' => 1, 'sanction_type' => 'ban', 'reason' => 'lol']);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'success' => __('sanction.contest.success'),
            'redirect' => url('/sanctions/contest/2')
        ]);
        $this->assertEquals(2, Contest::count());
        $this->assertEquals(1, Contest::where('sanction_id', 1)
            ->where('sanction_type', 'ban')
            ->where('user_id', 1)
            ->where('status', 'PENDING')
            ->where('reason', 'lol')
            ->count()
        );
    }

    public function testCloseUnlogged()
    {
        $response = $this->delete('/sanctions/contest/1');
        $response->assertStatus(302);
    }

    public function testCloseWithoutPermission()
    {
        $user = \App\User::find(2);
        $this->be($user);

        $response = $this->delete('/sanctions/contest/1');
        $response->assertStatus(403);
    }

    public function testCloseNotFoundContest()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->delete('/sanctions/contest/10');
        $response->assertStatus(404);
    }

    public function testClose()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->delete('/sanctions/contest/1');
        $response->assertStatus(200);
        $response->assertJson(['status' => true, 'success' => 'Contest closed.']);

        $this->assertEquals(1, Contest::where('id', 1)->where('status', 'CLOSED')->count());
        $this->assertEquals(1, ContestsHistory::where('contest_id', 1)->where('action', 'CLOSE')->where('user_id', 1)->count());
    }

    public function testEditUnlogged()
    {
        $response = $this->put('/sanctions/contest/1');
        $response->assertStatus(302);
    }

    public function testEditWithoutPermission()
    {
        $user = \App\User::find(2);
        $this->be($user);

        $response = $this->put('/sanctions/contest/1');
        $response->assertStatus(403);
    }

    public function testEditNotFoundContest()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->put('/sanctions/contest/10');
        $response->assertStatus(404);
    }

    public function testEditWithoutOrInvalidFields()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->put('/sanctions/contest/1', []);
        $response->assertStatus(400);
        $response->assertJson(['status' => true, 'success' => 'Missing or invalid type.']);

        $response = $this->put('/sanctions/contest/1', ['type' => 'fake']);
        $response->assertStatus(400);
        $response->assertJson(['status' => true, 'success' => 'Missing or invalid type.']);

        $response = $this->put('/sanctions/contest/1', ['type' => 'REDUCE']);
        $response->assertStatus(400);
        $response->assertJson(['status' => true, 'success' => 'Missing or invalid duration.']);

        $response = $this->put('/sanctions/contest/1', ['type' => 'REDUCE', 'end_date' => 'fake']);
        $response->assertStatus(400);
        $response->assertJson(['status' => true, 'success' => 'Missing or invalid duration.']);
    }

    public function testEditReduce()
    {
        if (!class_exists('ApiObsifight'))
            require base_path('vendor/eywek/obsifight/API/ApiObsifight.class.php');
        $api = $this->getMockBuilder(\ApiObsifight::class)
            ->setMethods(['get'])
            ->setConstructorArgs([env('API_OBSIFIGHT_USER'), env('API_OBSIFIGHT_PASS')])
            ->getMock();
        $api->method('get')
            ->willReturn((object)['status' => true, 'success' => true, 'body' => []]);
        $this->app->instance('\ApiObsifight', $api);

        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->put('/sanctions/contest/1', ['type' => 'REDUCE', 'end_date' => date('Y-m-d H:i:s', strtotime('+1 month'))]);
        $response->assertStatus(200);
        $response->assertJson(['status' => true, 'success' => 'Contest edited.']);

        $this->assertEquals(1, Contest::where('id', 1)->where('status', 'CLOSED')->count());
        $this->assertEquals(1, ContestsHistory::where('contest_id', 1)->where('action', 'CLOSE')->where('user_id', 1)->count());
    }

    public function testEditUnban()
    {
        if (!class_exists('ApiObsifight'))
            require base_path('vendor/eywek/obsifight/API/ApiObsifight.class.php');
        $api = $this->getMockBuilder(\ApiObsifight::class)
            ->setMethods(['get'])
            ->setConstructorArgs([env('API_OBSIFIGHT_USER'), env('API_OBSIFIGHT_PASS')])
            ->getMock();
        $api->method('get')
            ->willReturn((object)['status' => true, 'success' => true, 'body' => []]);
        $this->app->instance('\ApiObsifight', $api);

        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->put('/sanctions/contest/1', ['type' => 'UNBAN']);
        $response->assertStatus(200);
        $response->assertJson(['status' => true, 'success' => 'Contest edited.']);

        $this->assertEquals(1, Contest::where('id', 1)->where('status', 'CLOSED')->count());
        $this->assertEquals(1, ContestsHistory::where('contest_id', 1)->where('action', 'CLOSE')->where('user_id', 1)->count());
    }

    public function testAddCommentUnlogged()
    {
        $response = $this->post('/sanctions/contest/1/comment');
        $response->assertStatus(302);
    }

    public function testAddCommentWithoutPermission()
    {
        $user = \App\User::find(2);
        $this->be($user);

        $response = $this->post('/sanctions/contest/1/comment');
        $response->assertStatus(403);
    }

    public function testAddCommentWithNotFoundContest()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/sanctions/contest/10/comment');
        $response->assertStatus(404);
    }

    public function testAddCommentWithoutFields()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/sanctions/contest/1/comment');
        $response->assertStatus(400);
        $response->assertJson(['status' => true, 'success' => 'Missing content.']);
    }

    public function testAddComment()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/sanctions/contest/1/comment', ['content' => 'Contenu']);
        $response->assertStatus(200);
        $response->assertJson(['status' => true, 'success' => 'Commented.']);
        $this->assertEquals(1, ContestsComment::where('contest_id', 1)->where('user_id', 1)->where('content', 'Contenu')->count());
    }
}