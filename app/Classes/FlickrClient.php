<?php


namespace App\Classes;


use App\Models\FlickrOauth;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Str;
use Throwable;

class FlickrClient
{
    /** @var string */
    private $consumerKey;

    /** @var string */
    private $consumerSecretKey;

    /** @var string */
    private $requestTokenEndpoint;

    /** @var string */
    private $authEndpoint;

    /** @var string */
    private $requestAccessTokenEndpoint;

    /** @var string */
    private $restEndpoint;

    /** @var string */
    private $uploadEndpoint;

    /** @var string */
    private $replaceEndpoint;

    public function __construct()
    {
        $this->requestTokenEndpoint = 'https://www.flickr.com/services/oauth/request_token';
        $this->authEndpoint = 'https://flickr.com/services/auth/';
        $this->requestAccessTokenEndpoint = 'https://www.flickr.com/services/oauth/access_token';
        $this->restEndpoint = 'https://api.flickr.com/services/rest/';
        $this->uploadEndpoint = 'https://up.flickr.com/services/upload/';
        $this->replaceEndpoint = 'https://up.flickr.com/services/replace/';
    }

    public function setConsumerKey($consumerKey)
    {
        $this->consumerKey = $consumerKey;
    }

    public function setConsumerSecretKey($consumerSecretKey)
    {
        $this->consumerSecretKey = $consumerSecretKey;
    }

    /**
     * @param $method
     * @param $url
     * @param $params
     * @param null $oauthTokenSecret
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendFlickrRequest($method, $url, $params, $oauthTokenSecret = null)
    {
        $params['oauth_consumer_key'] = $this->consumerKey;
        $params['oauth_timestamp'] = time();
        $params['oauth_nonce'] = md5(mt_rand());
        $params['oauth_signature_method'] = 'HMAC-SHA1';
        $params['oauth_version'] = '1.0';
        ksort($params);

        $signatureBase = $method . '&' . rawurlencode($url) . '&';
        foreach ($params as $key => $value) {
            if ($key !== 'photo') {
                $signatureBase .= rawurlencode($key . '=' . rawurlencode($value) . '&');
            }
        }
        if (Str::endsWith($signatureBase, rawurlencode('&'))) {
            $signatureBase = substr($signatureBase, 0, (strlen($signatureBase) - strlen(rawurlencode('&'))));
        }

        $signatureKey = $this->consumerSecretKey . '&';
        if ($oauthTokenSecret) { // TODO: what's this? rename it?
            $signatureKey .= $oauthTokenSecret;
        }
        $signature = base64_encode(hash_hmac('sha1', $signatureBase, $signatureKey, true));

        $parameters = [];
        foreach ($params as $key => $value) {
            if ($key === 'photo') {
                $parameters[$key] = fopen($value, 'r'); // TODO: what's this? is this the file?
            } else {
                $parameters[$key] = $value;
            }
        }
        $parameters['oauth_signature'] = $signature;

        $client = new Client;
        $response = null;
        switch ($method) {
            case 'GET':
                $response = $client->request('GET', $url, ['query' => $parameters]);
                break;
            case 'POST':
                $response = $client->request('POST', $url, ['multipart' => $this->convertToMultipartParams($parameters)]);
                break;
        }
        if ($response->getStatusCode() === 200) {
            return $response->getBody()->getContents();
        }
        return null;
    }

    /**
     * Converts a list of parameters to a multi-part parameter list to be passed
     * to a Guzzle POST request.
     *
     * @param $params
     * @return array
     */
    private function convertToMultipartParams($params)
    {
        $multipart = [];
        foreach ($params as $key => $value) {
            $multipart[] = [
                'name' => $key,
                'contents' => $value,
            ];
        }
        return $multipart;
    }

    /**
     * Splits out the attributes from a JSON Flickr response into an associative array.
     *
     * @param $response
     * @return array
     */
    private function parseJsonResponse($response)
    {
        $contents = explode('&', $response);
        $attrs = [];
        foreach ($contents as $content) {
            list($key, $value) = explode('=', $content);
            $attrs[rawurldecode($key)] = rawurldecode($value);
        }
        return $attrs;
    }

    /**
     * Parses the attributes from an XML Flickr response into an associative array.
     *
     * @param $response
     * @return array
     */
    private function parseXmlResponse($response)
    {
        $xml = simplexml_load_string($response);
        return $this->xml2array($xml);
    }

    /**
     * Converts an XML object to an array.
     * @param $xmlObject
     * @param array $out
     * @return array
     */
    function xml2array($xmlObject, $out = [])
    {
        foreach ((array)$xmlObject as $index => $node)
            $out[$index] = (is_object($node) || is_array($node)) ? $this->xml2array($node) : $node;
        return $out;
    }

    /**
     * Generates a URL for the user to visit in order to authenticate Flickr with their
     * login credentials.
     *
     * @param FlickrOauth $oauth
     * @return string
     */
    public function generateRequestURL($oauth)
    {
        return 'https://www.flickr.com/services/oauth/authorize?perms=delete&oauth_token=' . rawurlencode($oauth->request_token);
    }

    /**
     * https://www.flickr.com/services/api/
     *
     * @param string $callbackURL
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRequestToken($callbackURL)
    {
        $response = $this->sendFlickrRequest('GET', $this->requestTokenEndpoint, [
            'oauth_callback' => $callbackURL,
        ]);
        if ($response === null) {
            throw new InvalidArgumentException('Unable to get a valid request_token response from Flickr.');
        }
        return $this->parseJsonResponse($response);
    }

    /**
     * This method will exchange a request token & verifier for an access token that
     * can be used to make api calls to Flickr.
     *
     * @param string $requestToken
     * @param string $requestTokenSecret
     * @param string $requestTokenVerifier
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function requestAccessToken($requestToken, $requestTokenSecret, $requestTokenVerifier)
    {
        $response = $this->sendFlickrRequest('GET', $this->requestAccessTokenEndpoint, [
            'oauth_token' => $requestToken,
            'oauth_verifier' => $requestTokenVerifier,
        ], $requestTokenSecret);
        if ($response === null) {
            throw new InvalidArgumentException('Unable to get a valid access_token response from Flickr.');
        }
        return $this->parseJsonResponse($response);
    }

    /**
     * @param $accessToken
     * @param $accessTokenSecret
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testAccessToken($accessToken, $accessTokenSecret)
    {
        try {
            $response = $this->sendFlickrRequest('GET', $this->restEndpoint, [
                'oauth_token' => $accessToken,
                'method' => 'flickr.auth.oauth.checkToken',
                'api_key' => $this->consumerKey,
            ], $accessTokenSecret);
            if ($response === null) {
                throw new InvalidArgumentException('Flickr access token appears to be invalid.');
            }
            return $this->parseXmlResponse($response);
        } catch (Throwable $e) {
            return null;
        }
    }
}
