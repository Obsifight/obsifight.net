<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\UsersEditUsernameHistory;
use App\UsersEmailEditRequest;
use App\UsersObsiguardIP;
use App\UsersTransferMoneyHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Validator;
use Yajra\Datatables\Facades\Datatables;

class UserController extends Controller
{

    public function transferHistory(Request $request)
    {
        return view('admin.user.transfer_history');
    }

    public function transferHistoryData(Request $request)
    {
        return Datatables::eloquent(UsersTransferMoneyHistory::with('user')->with('receiver')->orderBy('users_transfer_money_histories.id', 'DESC'))->make(true);
    }

    public function index(Request $request)
    {
        return view('admin.user.index');
    }

    public function find(Request $request)
    {
        return Datatables::eloquent(User::orderBy('id', 'DESC'))->make(true);
    }

    public function usernameHistory(Request $request)
    {
        return Datatables::eloquent(UsersEditUsernameHistory::with('user')->orderBy('users_edit_username_histories.id', 'DESC'))->make(true);
    }

    public function edit(Request $request)
    {
        $key = 'id';
        if (isset($request->username))
        {
            $key = 'username';
            $value = $request->username;
        }
        else
            $value = $request->id;
        // Find user
        $user = User::where($key, $value)
                    ->with('connectionLog')
                    ->with('usernameHistory')
                    ->with('obsiguardIP')
                    ->with('obsiguardLog')
                    ->with('refundHistory')
                    ->with('purchaseItemsHistory')
                    ->with('purchaseCreditsHistory')
                    ->with('purchaseItemsHistory.item')
                    ->with('twitterAccount')
                    ->with('youtubeChannel')
                    ->with('youtubeChannel.videos')
                    ->with('youtubeChannel.videos.remunerationHistory')
                    ->with('transferMoneyHistory')
                    ->with('transferMoneyHistory.user')
                    ->firstOrFail();

        return view('admin.user.view', compact('user'));
    }

    public function editData(Request $request)
    {
        $user = User::where('id', $request->id)->firstOrFail();
        if ($request->has('password')) {
            $user->password = User::hash($request->input('password'), $user->username);
            if (env('APP_FORUM_ENABLED', false))
            {
                try {
                    resolve(\Urb\XenforoBridge\XenforoBridge::class)->editUser($user->username, 'password', $request->input('password'));
                } catch (\Exception $e) {
                    Log::warning($e->getMessage());
                }
            }
        }
        if ($user->email != $request->input('email')) {
            if (Validator::make(['email' => $request->input('email')], ['email' => 'required|email'])->fails() || User::where('email', $request->input('email'))->count() > 0)
                return response()->json([
                    'status' => false,
                    'error' => __('user.signup.error.email')
                ]);
            $user->email = $request->input('email');
            if (env('APP_FORUM_ENABLED', false))
            {
                try {
                    resolve(\Urb\XenforoBridge\XenforoBridge::class)->editUser($user->username, 'email', $request->input('email'));
                } catch (\Exception $e) {
                    Log::warning($e->getMessage());
                }
            }
        }
        $user->save();
        return response()->json(['status' => true, 'success' => __('admin.users.update.success')]);
    }

    public function deleteObsiguardIP(Request $request)
    {
        UsersObsiguardIP::where('user_id', $request->id)->where('id', $request->ipId)->delete();
        return response()->json(['status' => true]);
    }

    public function emailsUpdate(Request $request)
    {
        $requests = UsersEmailEditRequest::with('user')->get();
        return view('admin.user.requests_email_update', compact('requests'));
    }

    public function emailsUpdateValid(Request $req)
    {
        $request = UsersEmailEditRequest::where('id', $req->id)->with('user')->firstOrFail();
        // Notify
        $notification = new \App\Notification();
        $notification->user_id = $request->user->id;
        $notification->type = 'success';
        $notification->key = 'user.profile.email.edit.success';
        $notification->vars = [];
        $notification->auto_seen = 1;
        $notification->save();
        // Edit user
        $request->user->email = $request->email;
        $request->user->save();
        if (env('APP_FORUM_ENABLED', false))
        {
            try {
                resolve(\Urb\XenforoBridge\XenforoBridge::class)->editUser($request->user->username, 'email', $request->email);
            } catch (\Exception $e) {
                Log::warning($e->getMessage());
            }
        }
        $request->delete();
        return response()->json(['status' => true]);
    }

    public function emailsUpdateInvalid(Request $request)
    {
        $request = UsersEmailEditRequest::where('id', $request->id)->with('user')->firstOrFail();
        // Notify
        $notification = new \App\Notification();
        $notification->user_id = $request->user->id;
        $notification->type = 'error';
        $notification->key = 'user.profile.email.edit.unsuccess';
        $notification->vars = [];
        $notification->auto_seen = 1;
        $notification->save();
        $request->delete();
        return response()->json(['status' => true]);
    }

}
