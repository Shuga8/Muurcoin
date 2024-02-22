<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Admin;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\StoreAdminRequest;

class AdminController extends Controller
{

    public function index()
    {

        $widget['total_users'] = User::count();
        $widget['verified_users'] = User::active()->count();
        $widget['email_unverified_users']  = User::emailUnverified()->count();
        $widget['email_verified_users']  = User::emailVerified()->count();

        $transactions = Transaction::all()->map(function ($transaction) {
            $transaction->created_at = $transaction->created_at->format('Y-m-d');
            return $transaction;
        });

        $trxReport['date'] = collect([]);
        $plusTrx = Transaction::where(function ($query) {
            $query->where('trx_type', 'Deposit')
                ->orWhere(function ($query) {
                    $query->where('trx_type', 'Spot')
                        ->where('amount', '>', 0);
                });
        })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw("SUM(amount) as amount, DATE_FORMAT(created_at,'%Y-%m-%d') as date")
            ->orderBy('created_at')
            ->groupBy('date')
            ->get();

        $plusTrx->map(function ($trxData) use ($trxReport) {
            $trxReport['date']->push($trxData->date);
        });

        $minusTrx = Transaction::where(function ($query) {
            $query->where('trx_type', 'Withdrawal')
                ->orWhere('trx_type', 'Transfer')
                ->orWhere(function ($query) {
                    $query->where('trx_type', 'Spot')
                        ->where('amount', '<', 0);
                });
        })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw("SUM(-1 * amount) as amount, DATE_FORMAT(created_at,'%Y-%m-%d') as date")
            ->orderBy('created_at')
            ->groupBy('date')
            ->get();

        $minusTrx->map(function ($trxData) use ($trxReport) {
            $trxReport['date']->push($trxData->date);
        });

        $trxReport['date'] = dateSorting($trxReport['date']->unique()->toArray());

        $data = [
            'title' => 'Dashboard',
            'widget' => $widget,
            'transactions' => $transactions,
            'trxReport' => $trxReport,
            'plusTrx' => $plusTrx,
            'minusTrx' => $minusTrx,
        ];

        return view('admin.dashboard')->with($data);
    }

    public function create()
    {
        if (auth('admin')->user()->role !== 'super-admin') {
            abort(403);
        }

        $data = [
            'title' => 'Create Admin',
        ];

        return view('admin.create')->with($data);
    }

    public function store(StoreAdminRequest $request)
    {
        if (auth('admin')->user()->role !== 'super-admin') {
            abort(403);
        }

        try {

            $formFields = [
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
                'password' => $request->password
            ];

            $formFields['password'] = bcrypt($formFields['password']);

            if (Admin::create($formFields)) {
                $notify[] = ['success', 'Admin Successfully Created'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'Failed to create admin'];
                return back()->withNotify($notify);
            }
        } catch (QueryException $e) {
            $notify[] = ['error', 'Failed to create admin: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }
}
