<?php

namespace App\Http\Controllers\Admin;

use App\UsersTransferMoneyHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

}
