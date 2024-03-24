<?php

namespace App\Jobs;

use App\Http\Services\AmazonService;
use App\Models\AmazonAdvertiseAPlus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetAPlusContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private AmazonService $amazonService, private string $asin)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $aplusContent = $this->amazonService->getAPlusContent($this->asin);

        if (empty($aplusContent['publish_record_list'])) {
            return;
        }

        foreach ($aplusContent['publish_record_list'] as $content) {
            AmazonAdvertiseAPlus::updateOrCreate(
                [
                    'amazon_advertises_item_id' => $content['asin'],
                    'content_reference_key' => $content['content_reference_key'],
                ],
                [
                    'content_type' => $content['content_type']->value,
                ]
            );
        }
    }
}
