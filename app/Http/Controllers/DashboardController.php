<?php

namespace App\Http\Controllers;

use App\Http\Services\AmazonService;
use App\Models\AmazonAdvertise;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function test()
    {
        $service = new AmazonService(auth()->user()->accounts->first());
        // $request = $service->searchAdvertises(['L1-0416+_FBA']);
        // $request = $service->getListing('L1-0416');
        $request = $service->getListing('L1-0009');
        // $request = $service->getAPlusContent('B0BR8K5J6W');
        // $request = $service->getAPlusContent('B0BZDQ4YJJ');

        dd($request);
    }

    public function index()
    {
        $advertises = AmazonAdvertise::whereAccountId(auth()->user()->accounts->pluck('id'))->get()->toArray();

        return Inertia::render('Dashboard', [
            'advertises' => $advertises,
        ]);
    }
}
