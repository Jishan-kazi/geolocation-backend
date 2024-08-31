<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeolocationService;
use Exception;
use Illuminate\Support\Facades\Cache;

class GeolocationLogsController extends Controller
{
    protected $geolocationService;

    public function __construct(GeolocationService $geolocationService)
    {
        $this->geolocationService = $geolocationService;
    }

    public function index(Request $request)
    {
        try {
            $ipAddress = $request->query('ip_address', null);
            $cacheKey = 'geolocation_info_' . ($ipAddress ?? 'default');

            // Check if data is cached
            $response = Cache::remember($cacheKey, now()->addMinutes(1), function () use ($ipAddress) {
                return $this->geolocationService->getGeolocationData($ipAddress);
            });

            return response()->json(json_decode($response), 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
