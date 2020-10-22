<?php

namespace App\Providers;

use Google_Client;
use Google_Service_Drive;
use Illuminate\Support\ServiceProvider;

class GoogleDriveServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Google Drive Client
        $this->app->singleton('Google_Client', function () {

            $clientID = config('services.googledrive.client_id');
            $clientSecret = config('services.googledrive.client_secret');

            $client = new Google_Client;
            $client->setApplicationName(config('app.name'));
            $client->setScopes(Google_Service_Drive::DRIVE);
            $client->setClientId($clientID);
            $client->setClientSecret($clientSecret);
            $client->setRedirectUri(route('googledrive.redirect'));
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            return $client;
        });
    }

}
