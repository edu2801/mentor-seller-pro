<?php

namespace App\Http\Services;

use SellingPartnerApi\Configuration;
use SellingPartnerApi\Endpoint;

class AmazonService
{
    public function __construct(

    ) {}

    private static $mtkid = 'A2Q3Y263D00KWC';

    public static function getConfig($account_id)
    {
        return new Configuration([
            "lwaClientId" => env('LWA_CLIENT_ID'),
            "lwaClientSecret" => env('LWA_CLIENT_SECRET'),
            "awsAccessKeyId" => env('AWS_ACCESS_KEY_ID'),
            "awsSecretAccessKey" => env('AWS_SECRET_ACCESS_KEY'),
            "endpoint" => Endpoint::NA,
            "roleArn" => env('ROLE_ARN'),
        ]);
    }
}