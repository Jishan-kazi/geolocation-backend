<?php

namespace App\Services;

use Exception;
use App\Models\GeolocationLog;

class GeolocationService
{
    public function getGeolocationData()
    {
        try {
            $api_key = env('IPGEOLOCATION_API_KEY', "12d4c58a94ab4fd8bfee6343d668e53a");
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://ipgeolocation.abstractapi.com/v1/?api_key=" . $api_key,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                throw new Exception("Problem in fetching data");
            }


            $data = json_decode($response, true);

            // Check if latitude and longitude are present
            if (isset($data['latitude']) && isset($data['longitude'])) {
                // Check if the record with the same IP address exists
                $existingRecord = GeolocationLog::where('ip_address', $data['ip_address'])->first();

                if (!$existingRecord) {
                    GeolocationLog::create([
                        'ip_address' => $data['ip_address'],
                        'country'    => $data['country'],
                        'region'     => $data['region'],
                        'city'       => $data['city'],
                        'latitude'   => $data['latitude'],
                        'longitude'  => $data['longitude'],
                    ]);
                }
            }
            logger(['response' => $response, 'error' => null]);
            return $response;

        } catch (Exception $e) {
            if (isset($curl)) {
                curl_close($curl);
            }
            logger(['response' => null, 'error' => $e->getMessage()]);
            throw new Exception($e->getMessage());
        }
    }
}
