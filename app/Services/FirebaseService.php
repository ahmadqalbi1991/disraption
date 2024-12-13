<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\DynamicLink\ShortenLongDynamicLink;
use Kreait\Firebase\Contract\DynamicLinks;
use Kreait\Firebase\DynamicLink\ShortenLongDynamicLink\FailedToShortenLongDynamicLink;

class FirebaseService
{
    protected $database;
    protected $dynamicLinks;

    public function __construct()
    {
        $firebaseConfigPath = base_path(env('FIREBASE_CREDENTIALS'));

        $firebase = (new Factory)
            ->withServiceAccount($firebaseConfigPath)
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->database = $firebase->createDatabase();

        $factory = (new Factory)->withServiceAccount($firebaseConfigPath);
        $dynamicLinksDomain = 'https://mydrworld.page.link';
        $this->dynamicLinks = $factory->createDynamicLinksService($dynamicLinksDomain);
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function shortenUrl($longUrl)
    {
        //$longLink = 'https://mydrworld.page.link?'.$longUrl;
        try {
            echo $link = $this->dynamicLinks->shortenLongDynamicLink($longLink);
            //$link = $this->dynamicLinks->shortenLongDynamicLink($longLink, ShortenLongDynamicLink::WITH_UNGUESSABLE_SUFFIX);
            //echo $link = $this->dynamicLinks->shortenLongDynamicLink($longLink, ShortenLongDynamicLink::WITH_SHORT_SUFFIX);
        } catch (FailedToShortenLongDynamicLink $e) {
            echo $e->getMessage(); exit;
        }
        
    }

    
}