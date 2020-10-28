<?php

namespace App\Providers;

use App\Classes\FlickrClient;
use App\Classes\GoogleCloudPlatform;
use Google_Client;
use Google_Service_Drive;
use Illuminate\Support\ServiceProvider;

class ExternalServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // FlickrClient Client
        $this->app->singleton(FlickrClient::class, function() {
            $consumerKey = config('services.flickr.consumer_key');
            $consumerSecretKey = config('services.flickr.consumer_secret_key');

            $client = new FlickrClient;
            $client->setConsumerKey($consumerKey);
            $client->setConsumerSecretKey($consumerSecretKey);

            return $client;
        });

        // GoogleCloudPlatform Client
        $this->app->singleton(GoogleCloudPlatform::class, function() {
            $apiKey = config('services.googlecloudplatform.api_key');

            $client = new GoogleCloudPlatform;
            $client->setApiKey($apiKey);

            return $client;
        });

        // Google Drive Client
        $this->app->singleton(Google_Client::class, function () {

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
