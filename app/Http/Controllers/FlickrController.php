<?php

namespace App\Http\Controllers;

use App\Classes\FlickrClient;
use App\Models\FlickrOauth;
use Auth;
use Illuminate\Http\Request;
use InvalidArgumentException;

class FlickrController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * This will redirect the user to Flickr, prompting them for authorization.
     * Once complete, the user will be redirected back to the $callbackURL.
     *
     * @param $callbackURL
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Throwable
     */
    private function requestFlickrAccess($callbackURL)
    {
        $flickr = app()->make(FlickrClient::class);
        $response = $flickr->getRequestToken($callbackURL);
        if (!$response || !boolval($response['oauth_callback_confirmed'])) {
            throw new InvalidArgumentException('Response from Flickr was empty.');
        }
        $oauth = new FlickrOauth;
        $oauth->request_token = $response['oauth_token'];
        $oauth->request_token_secret = $response['oauth_token_secret'];
        $oauth->user()->associate(Auth::user());
        $oauth->saveOrFail();
        return redirect()->away($flickr->generateRequestURL($oauth));
    }

    /**
     * This endpoint will authenticate with Flickr, and then redirect to the
     * given URL. If the user isn't already authenticated, they will be redirected to
     * Flickr to authorize, after which they will then be redirected to the next_url
     * provided.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function authenticate(Request $request)
    {
        $data = $this->validate($request, [
            'next_url' => 'required|url',
        ]);

        // Check if the User is authenticated with Flickr
        /** @var FlickrOauth $oauth */
        $oauth = Auth::user()->flickrOauth()->latest()->first();
        if (!$oauth || strlen($oauth->access_token) <= 0) {
            return $this->requestFlickrAccess(route('flickr.oauth-callback', $data));
        }

        // Test the access token to see if it is still ok
        $flickr = app()->make(FlickrClient::class);
        $response = $flickr->testAccessToken($oauth->access_token, $oauth->access_token_secret);
        $validToken = $response['@attributes'] && $response['@attributes']['stat'] === 'ok' // check if token is valid
            && $response['oauth'] && $response['oauth']['perms'] === 'delete'; // check for 'delete' (all) permissions
        if (!$validToken) {
            return $this->requestFlickrAccess(route('flickr.oauth-callback', $data));
        }

        return redirect()->to($data['next_url']);
    }

    /**
     * This is the callback that is run after authenticating with Flickr.
     * After the user enters their credentials and authorizes access, they will be
     * redirected here.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function oauthCallback(Request $request)
    {
        /** @var FlickrOauth $oauth */
        $oauth = Auth::user()->flickrOauth()->latest()->first();

        // Validate the callback information
        $data = $this->validate($request, [
            'next_url' => 'required|url',
            'oauth_token' => 'required|string|in:' . $oauth->request_token,
            'oauth_verifier' => 'required|string',
        ]);

        // Request an Access Token from Flickr
        $flickr = app()->make(FlickrClient::class);
        $response = $flickr->requestAccessToken($oauth->request_token, $oauth->request_token_secret, $data['oauth_verifier']);

        // Check for errors
        if (!$response || !array_key_exists('oauth_token', $response) || strlen($response['oauth_token']) <= 0) {
            abort(401, 'Flickr authorization failed.');
        }

        // Save the response information
        $oauth->flickr_nsid = $response['user_nsid'];
        $oauth->flickr_name = $response['fullname'];
        $oauth->flickr_username = $response['username'];
        $oauth->request_token_verifier = $data['oauth_verifier'];
        $oauth->access_token = $response['oauth_token'];
        $oauth->access_token_secret = $response['oauth_token_secret'];
        $oauth->saveOrFail();

        // Redirect to the intended destination
        return redirect()->to($data['next_url']);
    }
}
