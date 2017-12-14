<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\UsersEditUsernameHistory;
use App\UsersObsiguardIP;
use App\UsersTransferMoneyHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{

    public function transferHistory(Request $request)
    {
        return view('admin.user.transfer_history');
    }

    public function transferHistoryData(Request $request)
    {
        return Datatables::of(UsersTransferMoneyHistory::with('user')->with('receiver')->orderBy('id', 'DESC')->get())->make(true);
    }

    public function index(Request $request)
    {
        return view('admin.user.index');
    }

    public function find(Request $request)
    {
        return Datatables::of(User::orderBy('id', 'DESC')->get())->make(true);
    }

    public function usernameHistory(Request $request)
    {
        return Datatables::of(UsersEditUsernameHistory::with('user')->orderBy('id', 'DESC')->get())->make(true);
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
                    ->with('twitterAccount')
                    ->with('youtubeChannel')
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
            resolve(\Urb\XenforoBridge\XenforoBridge::class)->editUser($user->username, 'password', $request->input('password'));
        }
        if ($user->email != $request->input('email')) {
            if (Validator::make(['email' => $request->input('email')], ['email' => 'required|email'])->fails() || User::where('email', $request->input('email'))->count() > 0)
                return response()->json([
                    'status' => false,
                    'error' => __('user.signup.error.email')
                ]);
            $user->email = $request->input('email');
        }
        $user->save();
        return response()->json(['status' => true, 'success' => __('admin.users.update.success')]);
    }

    public function deleteObsiguardIP(Request $request)
    {
        UsersObsiguardIP::where('user_id', $request->id)->where('id', $request->ipId)->delete();
        return response()->json(['status' => true]);
    }

}