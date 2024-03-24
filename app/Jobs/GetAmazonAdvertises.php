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
                'description',
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
        $itemsSkus = array_column($advertises, 'external_sku');
        foreach ($itemsSkus as $itemSku) {
            GetListing::dispatch($this->amazonService, $itemSku)->onQueue('listing');
        }

        $itemsId = AmazonAdvertise::select('item_id')->distinct()->where('account_id', $accountId)->pluck('item_id');
        foreach ($itemsId as $itemId) {
            GetAPlusContent::dispatch($this->amazonService, $itemId)->onQueue('aplus');
        }

        // unset($advertises);

        // $count = count($itemsId);
        // for ($i = 0; $i < $count; $i = $i + 20) {
        //     $itemsIdSlice = $itemsId->slice($i, 20)->toArray();
        //     $advertises = $this->amazonService->searchAdvertises($itemsIdSlice, 'ASIN');

        //     foreach ($advertises as $advertise) {
        //         AmazonAdvertise::where('item_id', $advertise['asin'])->where('account_id', $accountId)->update(['thumbnail' => $advertise['images'][0]['images'][1]['link'] ?? null]);
        //     }
        // }
    }
}
