<?php

namespace App\Http\Controllers;

use App\Classes\FlickrClient;
use App\Models\FlickrOauth;
use App\Models\FlickrPost;
use App\Models\Photograph;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
        $response = $flickr->testAccessToken($oauth);
        $validToken = array_key_exists('@attributes', $response) && array_key_exists('stat', $response['@attributes']) && $response['@attributes']['stat'] === 'ok' // check if token is valid
            && array_key_exists('oauth', $response) && $response['oauth']['perms'] === 'delete'; // check for 'delete' (all) permissions
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
        $oauth->request_token_verifier = $data['oauth_verifier'];
        $response = $flickr->requestAccessToken($oauth);

        // Check for errors
        if (!$response || !array_key_exists('oauth_token', $response) || strlen($response['oauth_token']) <= 0) {
            abort(401, 'Flickr authorization failed.');
        }

        // Save the response information
        $oauth->flickr_nsid = $response['user_nsid'];
        $oauth->flickr_name = $response['fullname'];
        $oauth->flickr_username = $response['username'];
        $oauth->access_token = $response['oauth_token'];
        $oauth->access_token_secret = $response['oauth_token_secret'];
        $oauth->saveOrFail();

        // Redirect to the intended destination
        return redirect()->to($data['next_url']);
    }

    /**
     * Shows the Flickr Post form (to create a new entry on Flickr).
     *
     * @param Photograph $photo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showPostForm(Photograph $photo)
    {
        return view('popup.flickr-post', compact('photo'));
    }

    /**
     * Submits a post to Flickr.
     *
     * @param Request $request
     * @param Photograph $photo
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Throwable
     */
    public function submitPost(Request $request, Photograph $photo)
    {
        // Validation
        $data = $this->validate($request, [
            'title' => 'present|string|max:255|nullable',
            'location' => 'present|string|max:255|nullable',
            'description' => 'present|string|max:2000|nullable',
            'tags' => 'present|array|max:30|nullable',
            'is_public' => 'required|boolean',
            'is_friend' => 'required|boolean',
            'is_family' => 'required|boolean',
        ]);

        // Process tags (trim and remove '#')
        $tags = $data['tags'] ?? [];
        for ($i = 0; $i < count($tags); $i++) {
            $tags[$i] = trim(ltrim($tags[$i], '#'));
        }
        $tags = json_encode($tags);

        /** @var FlickrOauth $oauth */
        $oauth = Auth::user()->flickrOauth()->latest()->first();
        if (strlen($oauth->access_token) <= 0) {
            throw ValidationException::withMessages([
                'oauth' => 'Flickr authorization not found.',
            ]);
        }

        // Send the post to Flickr
        /** @var FlickrClient $flickr */
        $flickr = app()->make(FlickrClient::class);
        $post = new FlickrPost($data);
        $post->flickrOauth()->associate($oauth);
        $post->user()->associate(Auth::user());
        $post->photograph()->associate($photo);
        $post->image_path = $photo->photographEdits('medium')->latest()->first()->getImagePath();
        $post->tags = $tags;
        $response = $flickr->submitPost($oauth, $post);
        if ($response === null) {
            abort(419, 'Unable to submit post to Flickr.');
        }
        $post->saveOrFail();

        // TODO: do we need to create Flickr "Albums" and post to them (using the PhotoCollections that are attached to the photograph)?

        // Return the created post
        return response()->json($post, 201);
    }
}
