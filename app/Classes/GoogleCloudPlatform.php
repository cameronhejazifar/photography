<?php


namespace App\Classes;


use GuzzleHttp\Client;
use Throwable;

class GoogleCloudPlatform
{
    /** @var string */
    private $apiKey;

    /** @var string */
    private $geocodeEndpoint;

    public function __construct()
    {
        $this->geocodeEndpoint = 'https://maps.googleapis.com/maps/api/geocode/json';
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Uses the google maps api to determine the latitude and longitude of a location string.
     *
     * @param $location
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function determineLocation($location)
    {
        try {
            if (strlen($location) <= 0) {
                return [null, null];
            }
            $client = new Client();
            $response = $client->request('GET', $this->geocodeEndpoint, [
                'query' => [
                    'key' => $this->apiKey,
                    'address' => $location,
                ],
            ]);
            if ($response->getStatusCode() === 200) {
                $content = $response->getBody()->getContents();
                $json = json_decode($content, true);
                if ($json['status'] === 'OK' && array_key_exists('results', $json) && count($json['results']) > 0) {
                    $result = $json['results'][0];
                    if (array_key_exists('geometry', $result) && array_key_exists('location', $result['geometry'])) {
                        $latLon = $result['geometry']['location'];
                        if (array_key_exists('lat', $latLon) && array_key_exists('lng', $latLon)) {
                            return [$latLon['lat'], $latLon['lng']];
                        }
                    }
                }
            }
            return [null, null];
        } catch (Throwable $e) {
            return [null, null];
        }
    }
}
