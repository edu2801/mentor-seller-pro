<?php

namespace App\Http\Controllers;

use App\Models\AmazonAdvertise;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $advertises = AmazonAdvertise::whereAccountId(auth()->user()->accounts->pluck('id'))->get()->toArray();

        return Inertia::render('Dashboard', [
            'advertises' => $advertises,
        ]);
    }
}
