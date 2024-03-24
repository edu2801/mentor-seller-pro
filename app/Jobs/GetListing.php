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
            echo "começou a salvar  \n";
            $advertise = AmazonAdvertise::where('external_sku', $listing['sku'])->where('account_id', $this->amazonService->account->id)->first();

            if (!$advertise) {
                $advertise = new AmazonAdvertise();
            }

            $advertise->external_sku = $listing['sku'];
            $advertise->account_id = $this->amazonService->account->id;
            $advertise->title = $listing['attributes']['item_name'][0]->value ??  $listing['summaries'][0]['item_name'] ?? "--sem título--";
            $advertise->description = $listing['attributes']['product_description'][0]->value ?? null;
            $advertise->bullet_points = isset($listing['attributes']['bullet_point']) ? json_encode($listing['attributes']['bullet_point']) : null;
            $advertise->keywords = $listing['attributes']['generic_keyword'][0]->value ?? null;
            // $advertise->status = $listing['attributes']['status'] ?? null;
            $advertise->thumbnail = $listing['attributes']['main_product_image_locator'][0]->media_location ?? null;
            // $advertise->variation = $listing['attributes']['variation'] ?? null;
            // $advertise->parent_sku = $listing['attributes']['parent_sku'] ?? null;
            // $advertise->permalink = $listing['attributes']['product_url'] ?? null;
            $advertise->price = $listing['attributes']['purchasable_offer'][0]->our_price[0]->schedule[0]->value_with_tax ?? null;
            // $advertise->sold_quantity = $listing['attributes']['sold_quantity'] ?? null;
            // $advertise->visits = $listing['attributes']['visits'] ?? null;
            $advertise->save();
 
        $advertise->images()->delete();

        for ($i = 1; $i < 10; $i++) {
            if (!isset($listing['attributes']['other_product_image_locator_' . $i])) {
                break;
            }
            $imageDimention = getimagesize($listing['attributes']['other_product_image_locator_' . $i][0]->media_location);
            AmazonAdvertiseImage::create([
                'amazon_advertise_id' => $advertise->id,
                'url' => $listing['attributes']['other_product_image_locator_' . $i][0]->media_location,
                'width' => $imageDimention[0] ?? 0,
                'height' => $imageDimention[1] ?? 0,
            ]);
        }

        AmazonAdvertise::calcGrade($advertise);
    }
}
