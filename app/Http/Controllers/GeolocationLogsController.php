<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeolocationService;
use Exception;

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
            $response = $this->geolocationService->getGeolocationData();
            return response()->json(json_decode($response), 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
