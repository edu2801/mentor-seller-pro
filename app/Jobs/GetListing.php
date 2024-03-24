<?php

namespace App\Jobs;

use App\Http\Services\AmazonService;
use App\Models\AmazonAdvertise;
use App\Models\AmazonAdvertiseImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetListing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private AmazonService $amazonService, private string $sku)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        print_r($this->sku);
        $listing = $this->amazonService->getListing($this->sku);

        if (empty($listing)) {
            return;
        }
        // 'item_id',
        // 'external_sku',
        // 'title',
        // 'description',
        // 'bullet_points',
        // 'keywords',
        // 'status',
        // 'thumbnail',
        // 'variation',
        // 'parent_sku',
        // 'permalink',
        // 'price',
        // 'sold_quantity',
        // 'visits',
        // 'account_id'
        AmazonAdvertise::updateOrCreate(
            [
                'external_sku' => $listing['sku'],
                'account_id' => $this->amazonService->account->id,
            ],
            [
                'title' => $listing['attributes']['item_name'][0]->value ?? null,
                'description' => $listing['attributes']['product_description'][0]->value ?? null,
                //'bullet_points' => $listing['attributes']['bullet_points'] ?? null,
                'keywords' => $listing['attributes']['generic_keyword'][0]->value ?? null,
                // 'status' => $listing['attributes']['status'] ?? null,
                'thumbnail' => $listing['attributes']['main_product_image_locator'][0]->media_location ?? null,
                // 'variation' => $listing['attributes']['variation'] ?? null,
                // 'parent_sku' => $listing['attributes']['parent_sku'] ?? null,
                // 'permalink' => $listing['attributes']['product_url'] ?? null,
                'price' => $listing['attributes']['purchasable_offer'][0]->our_price[0]->schedule[0]->value_with_tax ?? null,
                // 'sold_quantity' => $listing['attributes']['sold_quantity'] ?? null,
                // 'visits' => $listing['attributes']['visits'] ?? null,
            ]
        );

        $amazonAdvertise = AmazonAdvertise::where('external_sku', $this->sku)->where('account_id', $this->amazonService->account->id)->first();

        $amazonAdvertise->images()->delete();

        for ($i = 1; $i < 10; $i++) {
            if (!isset($listing['attributes']['other_product_image_locator_' . $i])) {
                break;
            }
            $imageDimention = getimagesize($listing['attributes']['other_product_image_locator_' . $i][0]->media_location);
            AmazonAdvertiseImage::create([
                'amazon_advertise_id' => $amazonAdvertise->id,
                'url' => $listing['attributes']['other_product_image_locator_' . $i][0]->media_location,
                'width' => $imageDimention[0] ?? 0,
                'height' => $imageDimention[1] ?? 0,
            ]);
        }
    }
}
