<?php

namespace App\Services;

use GuzzleHttp\Client;

class BankService
{
    public function bankListing()
    {
        $client = new Client();
        $url = "https://api.flutterwave.com/v3/banks/NG";

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('FLUTTERWAVE_SECRET_KEY'),
                    'Accept'        => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);

        } catch (\Exception $e) {
            return false;
        }
    }
}
