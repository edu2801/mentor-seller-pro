<?php

namespace App\Http\Controllers;

use App\Http\Services\AmazonService;
use App\Models\AmazonAdvertise;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function PHPUnit\Framework\isNull;

class DashboardController extends Controller
{
    public function test()
    {
        $service = new AmazonService(auth()->user()->accounts->first());
        // $request = $service->searchAdvertises(['L1-0416+_FBA']);
        // $request = $service->getListing('L1-0416');
        $request = $service->getListing('L1-0341+_FBA');
        // $request = $service->getAPlusContent('B0BR8K5J6W');
        // $request = $service->getAPlusContent('B0BZDQ4YJJ');

        dd($request);
    }

    public function index(User $user = null)
    {
        $userAccountsIds = auth()->user()->accounts->pluck('id');

        if (!is_null($user) && auth()->user()->role >= 1) {
            $userAccountsIds = $user->accounts->pluck('id');
        }

        if ($userAccountsIds->isEmpty()) {
            return Inertia::render('Dashboard', [
                'advertises' => [],
            ]);
        }

        $advertises = AmazonAdvertise::whereAccountId($userAccountsIds)->with('tasks')->get()->toArray();

        return Inertia::render('Dashboard', [
            'advertises' => $advertises,
        ]);
    }
}
