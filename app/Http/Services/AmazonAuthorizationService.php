<?php

namespace App\Http\Services;

use App\Models\UserMarketplaceAccount;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class AmazonAuthorizationService
{
    public function authorize(String $spApiOAuthCode, String $sellingPartnerId, Int $userId): UserMarketplaceAccount
    {
        $codeToToken = $this->codeToToken($spApiOAuthCode);
        $expirationTimeUnix = $codeToToken['expires_in'] + time();

        $userAmazonAccount = UserMarketplaceAccount::where('seller_id', $sellingPartnerId)->first();

        if ($userAmazonAccount) {
            $userAmazonAccount->update([
                'token' => $codeToToken['access_token'],
                'refresh_token' => $codeToToken['refresh_token'],
                'expiration_time' => date('Y-m-d H:i:s', $expirationTimeUnix)
            ]);

            return $userAmazonAccount;
        }

        $userAmazonAccount = UserMarketplaceAccount::create([
            'user_id' => $userId,
            'name' => 'Conta Amazon',
            'seller_id' => $sellingPartnerId,
            'token' => $codeToToken['access_token'],
            'refresh_token' => $codeToToken['refresh_token'],
            'expiration_time' => date('Y-m-d H:i:s', $expirationTimeUnix),
        ]);

        return $userAmazonAccount;
    }


    private function codeToToken(String $code): array
    {
        $request = Http::withBasicAuth(Crypt::decryptString(env('LWA_CLIENT_ID_NEW')), Crypt::decryptString(env('LWA_CLIENT_SECRET_NEW')))
            ->asForm()->post(
                'https://api.amazon.com/auth/o2/token',
                [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => 'https://app.gestorseller.com.br/redirect/amazon'
                ]
            );

        return $request->json();
    }
}