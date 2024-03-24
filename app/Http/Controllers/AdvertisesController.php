<?php

namespace App\Http\Controllers;

use App\Http\Services\AmazonService;
use App\Jobs\GetAPlusContent;
use App\Jobs\GetListing;
use App\Models\AmazonAdvertise;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdvertisesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(AmazonAdvertise $amazonAdvertise)
    {
        return Inertia::render('Advertise/AdvertiseView', [
            'advertise' => $amazonAdvertise->load('images')->load('grades')->toArray()
        ]);
    }

    /**
     * Sync the specified resource.
     */
    public function sync(AmazonAdvertise $amazonAdvertise)
    {
        $service = new AmazonService($amazonAdvertise->account);

        GetListing::dispatchSync($service, $amazonAdvertise->external_sku);
        GetAPlusContent::dispatchSync($service, $amazonAdvertise->item_id);

        return to_route('advertise.show', $amazonAdvertise->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AmazonAdvertise $amazonAdvertise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AmazonAdvertise $amazonAdvertise)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AmazonAdvertise $amazonAdvertise)
    {
        //
    }
}
