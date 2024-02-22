<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Nette\Utils\Random;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ManageUsersController extends Controller
{

    public function allUsers()
    {

        $data = [
            'title' => 'All Users',
            'users' => $this->userData()
        ];
        return view('admin.users.list')->with($data);
    }

    public function activeUsers()
    {

        $data = [
            'title' => 'All Users',
            'users' => $this->userData('active'),
        ];
        return view('admin.users.list')->with($data);
    }

    protected function userData($scope = null)
    {
        if ($scope) {
            $users = User::$scope();
        } else {
            $users = User::query();
        }
        return $users->searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function bannedUsers()
    {
        $data = [
            'title' => 'Banned Users',
            'users' => $this->userData('banned')
        ];

        return view('admin.users.list')->with($data);
    }

    public function unverifiedEmailUsers()
    {
        $data = [
            'title' => 'Unverified Email Users',
            'users' => $this->userData('emailUnverified')
        ];

        return view('admin.users.list')->with($data);
    }

    public function verifiedEmailUsers()
    {

        $data = [
            'title' => 'Verified Email Users',
            'users' => $this->userData('emailVerified')
        ];

        return view('admin.users.list')->with($data);
    }

    public function detail(int $id)
    {
        $user = User::findOrFail($id);

        $widget['total_deposit']     = Transaction::where('user_id', $user->id)->where('trx_type', 'Deposit')->count();
        $widget['total_withdrawals'] = Transaction::where('user_id', $user->id)->where('trx_type', 'Withdrawal')->count();
        $widget['total_exchanges'] = Transaction::where('user_id', $user->id)->where('trx_Type', 'Spot')->count();

        $widget['total_transaction'] = Transaction::where('user_id', $user->id)->count();

        $data = [
            'title' => ucwords($user->username) . "'s Details",
            'user' => $user,
            'widget' => $widget,
        ];

        return view('admin.users.details')->with($data);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'wallet' => 'required|string',
            'act' => 'required|in:add,sub',
            'remark' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $amount = $request->amount;
        $trx = getTrx();
        $wallet = $request->wallet;

        if (!$this->checkIfSymbolExists($wallet, $user)) {
            $notify[] = ["error", " $request->wallet does not exits"];
            return back()->withNotify($notify);
        }

        $balance = json_decode($user->balance, true);


        if ($request->act == 'add') {

            try {
                DB::beginTransaction();

                $balance[$wallet] += $amount;

                $user->balance = json_encode($balance);

                $transaction = [
                    'user_id' => $user->id,
                    'reference_id' => uniqid() . now() . Random::generate(10),
                    'amount' => $amount,
                    'wallet' => $wallet,
                    'trx_type' => 'Deposit',
                    'post_balance' => (float) $balance[$wallet],
                    'details' => "$amount$wallet was Deposited",
                    'status' => 'completed'
                ];

                \App\Models\Transaction::create($transaction);

                $user->save();

                DB::commit();

                $notify[] = ["success", "Balance for $request->wallet updated successfully"];

                return back()->withNotify($notify);
            } catch (\Exception $e) {
                DB::rollBack();

                $notify[] = ["error", $e->getMessage()];

                return back()->withNotify($notify);
            }
        } else {

            try {
                DB::beginTransaction();

                $balance[$wallet] -= $amount;

                $user->balance = json_encode($balance);

                $transaction = [
                    'user_id' => $user->id,
                    'reference_id' => uniqid() . now() . Random::generate(10),
                    'amount' => $amount,
                    'wallet' => $wallet,
                    'trx_type' => 'Withdrawal',
                    'post_balance' => (float) $balance[$wallet],
                    'details' => "$amount$wallet was Withdrawn",
                    'status' => 'completed'
                ];

                \App\Models\Transaction::create($transaction);

                $user->save();

                DB::commit();

                $notify[] = ["success", "Balance for $request->wallet updated successfully"];

                return back()->withNotify($notify);
            } catch (\Exception $e) {
                DB::rollBack();

                $notify[] = ["error", $e->getMessage()];

                return back()->withNotify($notify);
            }
        }
    }

    public function checkIfSymbolExists($symbol, $user)
    {
        $balance = $user->balance;

        $balance = (array) json_decode($balance);

        if (!array_key_exists($symbol, $balance)) {
            return false;
        } else {
            return true;
        }
    }
}
