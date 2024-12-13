<?php
namespace App\Services;

//use Google_Client;

class FirebaseServices
{
    public function getAccessToken()
    {
        dd('there');
        // Path to the service account key file
        $keyFilePath = storage_path('app/google-service-account.json');

        // Create a Google Client
        $client = new Google_Client();
        $client->setAuthConfig($keyFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        // Generate an access token
        $accessToken = $client->fetchAccessTokenWithAssertion();

        // Return the access token
        return $accessToken['access_token'];
    }
}
