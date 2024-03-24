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
                'title',
                'status',
                'variation',
                'parent_sku',
                'permalink',
                'price',
                'sold_quantity',
                'visits',
            ]
        );

        $accountId = $advertises[0]['account_id'];
        $itemsIdWithoutThumbnail = AmazonAdvertise::select('item_id')->distinct()->whereNull('thumbnail')->whereAccountId($accountId)->pluck('item_id');
        unset($advertises);

        $count = count($itemsIdWithoutThumbnail);
        for ($i = 0; $i < $count; $i = $i + 20) {
            $itemsIdSlice = $itemsIdWithoutThumbnail->slice($i, 20)->toArray();
            $advertises = $this->amazonService->searchAdvertises($itemsIdSlice, 'ASIN');

            foreach ($advertises as $advertise) {
                AmazonAdvertise::where('item_id', $advertise['asin'])->where('account_id', $accountId)->update(['thumbnail' => $advertise['images'][0]['images'][1]['link'] ?? null]);
            }
        }
    }
}
