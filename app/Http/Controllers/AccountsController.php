<?php

namespace App\Http\Controllers;

use App\Http\Services\AmazonService;
use App\Jobs\GetAmazonAdvertises;
use App\Models\User;
use App\Models\UserMarketplaceAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user = null)
    {
        $userId = auth()->id();

        if (!is_null($user) && auth()->user()->role >= 1) {
            $userId = $user->id;
        }

        $accounts = UserMarketplaceAccount::select('id', 'name', 'seller_id', 'created_at')->whereUserId($userId)->get()->toArray();
        return Inertia::render('Accounts', ['accounts' => $accounts]);
    }

    /**
     * Sync the account advertises.
     */
    public function sync(UserMarketplaceAccount $account)
    {
        $service = new AmazonService($account);
        GetAmazonAdvertises::dispatch($service)->onQueue('advertises_report');

        return response()->json(['message' => 'A conta e seus anúncios estão sendo sincronizados.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
