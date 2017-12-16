<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait;

    protected $fillable = ['username', 'email', 'password', 'ip'];

    public function connectionLog()
    {
        return $this->hasMany('App\UsersConnectionLog');
    }

    public function usernameHistory()
    {
        return $this->hasMany('App\UsersEditUsernameHistory');
    }

    public function obsiguardIP()
    {
        return $this->hasMany('App\UsersObsiguardIP');
    }

    public function obsiguardLog()
    {
        return $this->hasMany('App\UsersObsiguardLog');
    }

    public function refundHistory()
    {
        return $this->hasOne('App\UsersRefundHistory');
    }

    public function twitterAccount()
    {
        return $this->hasOne('App\UsersTwitterAccount');
    }

    public function youtubeChannel()
    {
        return $this->hasOne('App\UsersYoutubeChannel');
    }

    public function transferMoneyHistory()
    {
        return $this->hasMany('App\UsersTransferMoneyHistory');
    }

    public function purchaseItemsHistory()
    {
        return $this->hasMany('App\ShopItemsPurchaseHistory');
    }

    public function purchaseCreditsHistory()
    {
        return $this->hasMany('App\ShopCreditHistory');
    }

    static public function hash($password, $username)
    {
        return sha1($username . 'PApVSuS8hDUEsOEP0fWZESmODaHkXVst27CTnYMM' . $password);
    }

    static public function getStatsFromUsername($username)
    {
        $body = @file_get_contents(env('DATA_SERVER_ENDPOINT') . '/users/' . $username);
        if (!$body) return false;
        $data = @json_decode($body);
        if (!$data) return false;
        if (!$data->status) return false;
        $user = $data->data;
        $user->faction = \App\Faction::getFactionFromUsername($username);
        return ($user);
    }

    static public function getSuccessList($uuid)
    {
        $body = @file_get_contents(env('DATA_SERVER_ENDPOINT') . '/users/' . $uuid . '/success');
        if (!$body) return false;
        $data = @json_decode($body);
        if (!$data) return false;
        if (!$data->status) return false;
        $rawSuccessList = $data->data;
        $successList = [];

        foreach ($rawSuccessList as $successName => $successValue) {
            if (is_bool($successValue)) {
                $successList[] = [
                    __('stats.success.' . $successName) => $successValue,
                ];
            } else {
                $array = [];
                foreach ($successValue as $value => $percentage) {
                    $array[__('stats.success.' . $successName, ['number' => $value])] = round($percentage, 0.1);
                }
                $successList[] = $array;
            }
        }

        return $successList;
    }

    public static function getStaff()
    {
        $staffList = [];
        $staff = json_decode(file_get_contents('http://api.obsifight.net/users/staff'))->data;
        $staffColors = ['red', 'red', 'green', 'olive', 'yellow'];
        $i = 0;
        foreach ($staff as $group => $users) {
            if ($group === 'Fondateur')
                $group = 'Administrateur';
            if ($group === 'AnimTest' || $group === 'ChefAnim')
                $group = 'Animateur';
            if ($group === 'ChefModo' || $group === 'ModoTest')
                $group = 'ModoJoueur';
            $group = $group . 's';
            if (!isset($staffList[$group]))
                $staffList[$group] = ['color' => $staffColors[$i++], 'users' => []];
            foreach ($users as $user)
                array_push($staffList[$group]['users'], $user);
        }
        return ($staffList);
    }
}
