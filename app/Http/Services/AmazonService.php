<?php

namespace App\Http\Services;

use App\Models\AmazonAdvertise;
use App\Models\UserMarketplaceAccount;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use SellingPartnerApi\Api\ReportsV20210630Api;
use SellingPartnerApi\Configuration;
use SellingPartnerApi\Document;
use SellingPartnerApi\Endpoint;
use SellingPartnerApi\Model\ReportsV20210630\CreateReportSpecification;
use SellingPartnerApi\ReportType;

class AmazonService
{
    public function __construct(private UserMarketplaceAccount $account)
    {
    }


    private static $mtkid = 'A2Q3Y263D00KWC';

    public function config()
    {
        return new Configuration([
            "lwaClientId" => Crypt::decryptString(env('LWA_CLIENT_ID')),
            "lwaClientSecret" => Crypt::decryptString(env('LWA_CLIENT_SECRET')),
            "lwaRefreshToken" => $this->account->refresh_token,
            "awsAccessKeyId" => Crypt::decryptString(env('AWS_ACCESS_KEY_ID')),
            "awsSecretAccessKey" => Crypt::decryptString(env('AWS_SECRET_ACCESS_KEY')),
            "endpoint" => Endpoint::NA,
            "roleArn" => Crypt::decryptString(env('ROLE_ARN')),
        ]);
    }

    /**
     * Retrieve an array of AmazonAdvertise objects.
     *
     * @return AmazonAdvertise[]
     */
    public function getAdvertises(): array
    {
        $this->checkToken();

        $reportType = ReportType::GET_MERCHANT_LISTINGS_ALL_DATA;

        $sdk = new ReportsV20210630Api(self::config());

        $reportsList = $sdk->getReports(
            ['GET_MERCHANT_LISTINGS_ALL_DATA']
        );

        if (!empty($reportsList['reports'])) {
            foreach ($reportsList['reports'] as $requestedReport) {

                if ($requestedReport['processing_status'] == 'IN_PROGRESS' || $requestedReport['processing_status'] == 'IN_QUEUE') {
                    $maxTries = 5;
                    $tries = 0;

                    while (($requestedReport['processing_status'] == 'IN_PROGRESS' || $requestedReport['processing_status'] == 'IN_QUEUE') && $tries < $maxTries) {
                        sleep(5);
                        $requestedReport = $sdk->getReport($requestedReport['report_id']);
                        $tries++;
                    }

                    if ($requestedReport['processing_status'] != 'DONE') {
                        return [];
                    }

                    $documentId = $requestedReport['report_document_id'];

                    $documentInfo = $sdk->getReportDocument($documentId, $reportType['name']);

                    $docToDownload = new Document($documentInfo, $reportType);
                    $docToDownload->download();

                    $reportData = $docToDownload->getData();

                    $advertises = [];

                    foreach ($reportData as $advertise) {
                        if ($advertise['status'] == 'Incomplete' || $advertise['status'] == 'Inactive') {
                            continue;
                        }

                        $advertises[($advertise['seller-sku'] ?? $advertise['sku-do-vendedor'])] = new AmazonAdvertise([
                            'item_id' => $advertise['asin1'] ?? $advertise['asin 1'],
                            'external_sku' => $advertise['seller-sku'] ?? $advertise['sku-do-vendedor'],
                            'title' => $advertise['item-name'] ?? $advertise['nome-do-item'],
                            'thumbnail' => null,
                            'variation' => null,
                            'parent_sku' => null,
                            'permalink' => 'https://www.amazon.com.br/dp/' . ($advertise['asin1'] ?? $advertise['asin 1']),
                            'price' => floatval($advertise['price'] ?? $advertise['preço'] ?? 0),
                            'sold_quantity' => null,
                            'visits' => null,
                            'account_id' => $this->account->id,
                        ]);
                    }

                    return $advertises;
                }

                if ($requestedReport['processing_status'] == 'DONE' && strtotime($requestedReport['data_end_time']) > strtotime('-90 minutes')) {
                    $documentId = $requestedReport['report_document_id'];

                    $documentInfo = $sdk->getReportDocument($documentId, $reportType['name']);

                    $docToDownload = new Document($documentInfo, $reportType);
                    $docToDownload->download();

                    $reportData = $docToDownload->getData();

                    $advertises = [];

                    foreach ($reportData as $advertise) {
                        if (!empty($advertise['status']) && ($advertise['status'] == 'Incomplete' || $advertise['status'] == 'Inactive')) {
                            continue;
                        }

                        $advertises[($advertise['seller-sku'] ?? $advertise['sku-do-vendedor'])] = new AmazonAdvertise([
                            'item_id' => $advertise['asin1'] ?? $advertise['asin 1'],
                            'external_sku' => $advertise['seller-sku'] ?? $advertise['sku-do-vendedor'],
                            'title' => $advertise['item-name'] ?? $advertise['nome-do-item'],
                            'thumbnail' => null,
                            'variation' => null,
                            'parent_sku' => null,
                            'permalink' => 'https://www.amazon.com.br/dp/' . ($advertise['asin1'] ?? $advertise['asin 1']),
                            'price' => floatval($advertise['price'] ?? $advertise['preço'] ?? 0),
                            'sold_quantity' => null,
                            'visits' => null,
                            'account_id' => $this->account->id,
                        ]);
                    }

                    return $advertises;
                }
            }
        }

        $createReport = $sdk->createReport(
            new CreateReportSpecification([
                'report_type' => 'GET_MERCHANT_LISTINGS_ALL_DATA',
                'marketplace_ids' => [self::$mtkid]
            ])
        );

        $reportId = $createReport['report_id'];

        $report = $sdk->getReport($reportId);

        $maxTries = 5;
        $tries = 0;
        while (($report['processing_status'] == 'IN_PROGRESS' || $report['processing_status'] == 'IN_QUEUE') && $tries < $maxTries) {
            sleep(5);
            $report = $sdk->getReport($reportId);
            $tries++;
        }

        if ($report['processing_status'] != 'DONE') {
            return [];
        }

        $documentId = $report['report_document_id'];

        $documentInfo = $sdk->getReportDocument($documentId, $reportType['name']);

        $docToDownload = new Document($documentInfo, $reportType);
        $docToDownload->download();

        $reportData = $docToDownload->getData();

        $advertises = [];

        foreach ($reportData as $advertise) {
            if (isset($advertise['status']) && ($advertise['status'] == 'Incomplete' || $advertise['status'] == 'Inactive')) {
                continue;
            }

            $advertises[($advertise['seller-sku'] ?? $advertise['sku-do-vendedor'])] = new AmazonAdvertise([
                'item_id' => $advertise['asin1'] ?? $advertise['asin 1'],
                'external_sku' => $advertise['seller-sku'] ?? $advertise['sku-do-vendedor'],
                'title' => $advertise['item-name'] ?? $advertise['nome-do-item'],
                'thumbnail' => null,
                'variation' => null,
                'parent_sku' => null,
                'permalink' => 'https://www.amazon.com.br/dp/' . ($advertise['asin1'] ?? $advertise['asin 1']),
                'price' => floatval($advertise['price'] ?? $advertise['preço'] ?? 0),
                'sold_quantity' => null,
                'visits' => null,
                'account_id' => $this->account->id,

            ]);
        }

        return $advertises;
    }

    public function checkToken()
    {
        if ($this->account->seller_id == 'A239634PEUS1QM') {
            return ['status' => true, 'data' => null];
        }

        if ($this->account->expiration_time < date('Y-m-d H:i:s', strtotime('+2 minute')) || empty($this->account->expitarion_time)) {
            $response = Http::withBasicAuth(Crypt::decryptString(env('LWA_CLIENT_ID')), Crypt::decryptString(env('LWA_CLIENT_SECRET')))
                ->asForm()->post(
                    'https://api.amazon.com/auth/o2/token',
                    [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $this->account->refresh_token,
                    ]
                );

            if ($response->successful()) {
                $refreshToken = $response->json();
                $this->account->token = $refreshToken['access_token'];
                $this->account->refresh_token = $refreshToken['refresh_token'];
                $this->account->expiration_time = date('Y-m-d H:i:s', strtotime('+' . $refreshToken['expires_in'] . ' seconds'));
                $this->account->save();
                return [
                    "data" => $response->body(),
                    "status" => true
                ];
            }

            if ($response->failed()) {
                return [
                    "data" => $response->body(),
                    "status" => false
                ];
            }
        }

        return [
            "data" => null,
            "status" => true
        ];
    }
}
