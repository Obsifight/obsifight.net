<?php
namespace App\Http\Controllers;

use App\Contest;
use App\ContestsComment;
use App\ContestsHistory;
use Validator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\User;

use Carbon\Carbon;

class ContestController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale(\Config::get('app.locale'));
        Carbon::setToStringFormat('d/m/Y à H:i:s');
    }

    public function index()
    {
        if (Auth::user()) {
            // Get user's sanctions
            $sanctions = [];
            $result = resolve('\ApiObsifight')->get("/user/" . Auth::user()->username . "/sanctions?active=1");
            if ($result->status) {
                $sanctionsList = $result->body;
                foreach ($sanctionsList as $type => $list) { // For each type (bans / kicks)
                    $type = substr($type, 0, -1);
                    foreach ($list as $sanction) { // For each sanctions
                        $sanctions[] = (object)array_merge($sanction, [ // Add to array
                            'type' => $type,
                            'date' => (new Carbon($sanction['date'])),
                            'contest' => Contest::where('sanction_id', $sanction['id'])
                                ->where('sanction_type', $type)
                                ->where(function ($query) {
                                    $query->where('status', 'PENDING')
                                        ->orWhere(function ($q) {
                                            $q->where('status', 'CLOSED')
                                                ->whereRaw('updated_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)'); // interval 1 month before re-contest
                                        });
                                })
                                ->first()
                        ]);
                    }
                }
            }
        }

        // Set public data
        $contests = Contest::orderBy('id', 'desc')->limit(5)->get();

        return view('contest.index', compact('sanctions', 'contests'));
    }

    public function add(Request $request) {
        if (!$request->has('sanction') || !$request->has('sanction_type') || !in_array($request->input('sanction_type'), ['ban', 'mute']) || !$request->has('reason'))
            return response()->json([
                'status' => false,
                'error' => __('form.error.fields')
            ]);
        // configure api
        $api = resolve('\ApiObsifight');

        // check sanction
        $result = $api->get("/sanction/" . $request->input('sanction_type') . "s/{$request->input('sanction')}");
        if (!$result->status)
            return response()->json([
                'status' => false,
                'error' => __('sanction.contest.error.api')
            ]);
        $sanction = $result->body[$request->input('sanction_type')];
        // check if active
        if (!$sanction['state'])
            return response()->json([
                'status' => false,
                'error' => __('sanction.contest.error.end')
            ]);
        // Check if not already in database
        $find = Contest::where('sanction_id', $request->input('sanction'))
            ->where('sanction_type', $request->input('sanction_type'))
            ->where(function ($query) {
                $query->where('status', 'PENDING')
                    ->orWhere(function ($q) {
                        $q->where('status', 'CLOSED')
                            ->whereRaw('updated_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)'); // interval 1 month before re-contest
                    });
            })
            ->first();
        if (!empty($find)) // already contested
            return response()->json([
                'status' => false,
                'error' => __('sanction.contest.error.already')
            ]);
        // create entry in db
        $contest = new Contest();
        $contest->sanction_id = $request->input('sanction');
        $contest->sanction_type = $request->input('sanction_type');
        $contest->user_id = Auth::user()->id;
        $contest->status = 'PENDING';
        $contest->reason = $request->input('reason');
        $contest->save();

        return response()->json([
            'status' => true,
            'success' => __('sanction.contest.success'),
            'redirect' => url('/sanctions/contest/' . $contest->id)
        ]);
    }

    public function view(Request $request) {
        $id = $request->id;
        // find
        $findContest = Contest::where('id', $id)->first();
        if (empty($findContest)) // not found
            return abort(404, 'Contest not found');

        // init api
        $api = resolve('\ApiObsifight');
        // find sanction
        $findSanction = $api->get("/sanction/{$findContest->sanction_type}s/{$findContest->sanction_id}");
        if (!$findSanction->status) // error
            return abort(404, 'Sanction not found');
        $sanction = $findSanction->body;
        $sanction = $sanction[$findContest->sanction_type];
        $sanction['date'] = new Carbon($sanction['date']);
        // get comments
        $comments = ContestsComment::where('contest_id', $findContest->id)->orderBy('id', 'desc')->get();
        // get history
        $histories = ContestsHistory::where('contest_id', $findContest->id)->orderBy('id', 'desc')->get();
        // find users infos + actions array
        $actions = array();
        foreach ($comments as $comment)
            array_push($actions, (object)['type' => 'comment', 'data' => $comment]);
        foreach ($histories as $history)
            array_push($actions, (object)['type' => 'history', 'data' => $history]);

        // order actions
        usort($actions, function($a, $b) {
            return $a->data->created_at->timestamp - $b->data->created_at->timestamp;
        });

        // render
        return view('contest.view', [
            'actions' => $actions,
            'contest' => $findContest,
            'sanction' => $sanction
        ]);
    }

    public function close(Request $request) {
        $id = $request->id;
        // find
        $findContest = Contest::where('id', $id)->first();
        if (empty($findContest)) // not found
            abort(404);
        // close
        $contest = Contest::find($id);
        $contest->status = 'CLOSED';
        $contest->save();
        // set into history
        $history = new ContestsHistory();
        $history->contest_id = $id;
        $history->action = 'CLOSE';
        $history->user_id = Auth::user()->id;
        $history->save();
        // send response
        return response()->json(['status' => true, 'success' => 'Contest closed.'], 200);
    }

    public function edit(Request $request) {
        $id = $request->id;
        // find
        $findContest = Contest::where('id', $id)->first();
        if (empty($findContest)) // not found
            abort(404);
        // check request
        if (!$request->has('type') || empty($request->input('type')) || !in_array($request->input('type'), ['REDUCE', 'UNBAN']))
            return response()->json(['status' => true, 'success' => 'Missing or invalid type.'], 400);
        if ($request->input('type') == 'REDUCE' && (!$request->has('end_date') || empty($request->input('end_date')) || date('Y-m-d H:i:s', strtotime($request->input('end_date'))) != $request->input('end_date')))
            return response()->json(['status' => true, 'success' => 'Missing or invalid duration.'], 400);

        // init api
        $api = resolve('\ApiObsifight');
        // action
        if ($request->input('type') == 'REDUCE') {
            $findSanction = $api->get("/sanction/{$findContest->sanction_type}s/{$findContest->sanction_id}", 'PUT', ['end_date' => date('Y-m-d H:i:s', strtotime($request->input('end_date')))]);
            if (!$findSanction->status) // error
                return response()->json(['status' => true, 'success' => 'Error when reduce with API.'], 500);
        } else if ($request->input('type') == 'UNBAN') {
            $findSanction = $api->get("/sanction/{$findContest->sanction_type}s/{$findContest->sanction_id}", 'PUT', ['remove_reason' => 'Contestation acceptée']);
            if (!$findSanction->status) // error
                return response()->json(['status' => true, 'success' => 'Error when reduce with API.'], 500);
        }

        // set into history
        $history = new ContestsHistory();
        $history->contest_id = $id;
        $history->action = strtoupper($request->input('type'));
        $history->user_id = Auth::user()->id;
        $history->save();

        // close
        $contest = Contest::find($id);
        $contest->status = 'CLOSED';
        $contest->save();
        // set into history
        $history = new ContestsHistory();
        $history->contest_id = $id;
        $history->action = 'CLOSE';
        $history->user_id = Auth::user()->id;
        $history->save();
        // send response
        return response()->json(['status' => true, 'success' => 'Contest edited.'], 200);
    }

    public function addComment(Request $request) {
        $id = $request->id;
        // find
        $findContest = Contest::where('id', $id)->first();
        if (empty($findContest)) // not found
            abort(404);
        // check request
        if (!$request->has('content') || empty($request->input('content')))
            return response()->json(['status' => true, 'success' => 'Missing content.'], 400);
        // add command
        $comment = new ContestsComment();
        $comment->contest_id = $id;
        $comment->content = $request->input('content');
        $comment->user_id = Auth::user()->id;
        $comment->save();
        // send response
        return response()->json(['status' => true, 'success' => 'Commented.'], 200);
    }

}