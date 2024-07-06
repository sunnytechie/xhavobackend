<?php

namespace App\Services;

use GuzzleHttp\Client;

class VerifyTxRefService
{
    public function verifyTransaction($tx_ref)
    {
        $client = new Client();
        $url = "https://api.flutterwave.com/v3/transactions/{$tx_ref}/verify";

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('FLUTTERWAVE_SECRET_KEY'),
                    'Accept'        => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['status']) && $data['status'] === 'success' && isset($data['data']['status']) && $data['data']['status'] === 'successful') {
                // Transaction is successful
                return true;
            } else {
                // Transaction is not successful
                return false;
            }

        } catch (\Exception $e) {
            return false;
        }
    }
}
