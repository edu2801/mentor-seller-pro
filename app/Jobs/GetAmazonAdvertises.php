<?php

namespace App\Jobs;

use App\Http\Services\AmazonService;
use App\Models\AmazonAdvertise;
use App\Models\UserMarketplaceAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetAmazonAdvertises implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private AmazonService $amazonService)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $advertises = $this->amazonService->getAdvertises();

        AmazonAdvertise::upsert(
            $advertises,
            ['external_sku', 'account_id'],
            [
                'external_sku',
                'title',
                'thumbnail',
                'variation',
                'parent_sku',
                'permalink',
                'price',
                'sold_quantity',
                'visits',
            ]
        );
    }
}
