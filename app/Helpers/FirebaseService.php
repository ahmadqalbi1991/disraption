<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Util\JSON;

class FirebaseService
{
    protected $databaseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->databaseUrl = config('firebase.FIREBASE_DATABASE_URL');
        $this->apiKey = config('firebase.FIREBASE_AUTH_KEY');
    }

    public function updateData($path, $data)
    {
        $url = $this->databaseUrl . $path . '.json?auth=' . $this->apiKey;

        $response = Http::patch($url, $data);

        if ($response->failed()) {
            return $response->body();
            //throw new \Exception('Error updating data in Firebase: ' . $response->body());
        }

        return $response->json();
    }



    public function setData($path, $data)
    {
        $url = $this->databaseUrl . $path . '.json?auth=' . $this->apiKey;

        $response = Http::put($url, $data);

        if ($response->failed()) {
            throw new \Exception('Error setting data in Firebase: ' . $response->body());
        }

        return $response->json();
    }

    // Optionally, add other methods for GET, POST, DELETE, etc.
}
