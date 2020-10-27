<?php

namespace App\Http\Controllers;

use App\Models\GoogleDriveOauth;
use Auth;
use BadMethodCallException;
use Carbon\Carbon;
use Exception;
use Google_Client;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function tryOAuth()
    {
        // Grab the Google Drive instance
        $google = app()->make(Google_Client::class);

        // Check if this user has a previous access token
        $oauth = Auth::user()->googleDriveOauth()->latest()->first();
        if ($oauth) {
            try {
                $google->setAccessToken(json_decode($oauth->token, true));
            } catch (Exception $e) {
                $oauth = null;
            }
        }

        // Check if the access token has expired (or is not set)
        if ($google->isAccessTokenExpired()) {

            if ($google->getRefreshToken()) {

                // Attempt to refresh the access token
                $token = $google->fetchAccessTokenWithRefreshToken($google->getRefreshToken());

                // If the access token couldn't be refreshed, then create a new one
                if ($google->isAccessTokenExpired()) {
                    return response()->redirectTo($google->createAuthUrl());
                }

                // If the access token was refreshed, save it to the DB
                $oauth = Auth::user()->googleDriveOauth()->create([
                    'refresh' => true,
                    'access_token' => $token['access_token'],
                    'refresh_token' => $token['refresh_token'],
                    'scope' => $token['scope'],
                    'token_type' => $token['token_type'],
                    'expires_at' => Carbon::parse($token['created'])->addSeconds($token['expires_in']),
                    'token' => json_encode($token),
                ]);

            } else {

                // The token couldn't be refreshed, so create a new one
                return response()->redirectTo($google->createAuthUrl());
            }
        }

        // If we made it to here, everything is ok! and we can return the successful oauth token
        return view('popup.googledrive-oauth', compact('oauth'));
    }

    /**
     * Callback (redirect uri) function that handles an OAuth response from the
     * Google Drive API.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Throwable
     */
    public function handleOAuthResponse(Request $request)
    {
        try {
            // Make sure a code has been returned from Google
            $data = $this->validate($request, [
                'code' => 'required|string',
                'scope' => 'required|string',
            ]);

            // Grab the Google Drive instance
            $google = app()->make(Google_Client::class);

            // Create a new access token using the code provided by Google
            $token = $google->fetchAccessTokenWithAuthCode($data['code']);

            // Check if the token is valid
            if ($google->isAccessTokenExpired()) {
                throw new BadMethodCallException('The access token response was invalid.');
            }

            // Save the OAuth information to the DB
            $oauth = new GoogleDriveOauth;
            $oauth->user()->associate(Auth::user());
            $oauth->refresh = false;
            $oauth->auth_code = $data['code'];
            $oauth->access_token = $token['access_token'];
            $oauth->refresh_token = $token['refresh_token'];
            $oauth->scope = $token['scope'];
            $oauth->token_type = $token['token_type'];
            $oauth->expires_at = Carbon::parse($token['created'])->addSeconds($token['expires_in']);
            $oauth->token = json_encode($token);
            $oauth->saveOrFail();

            // Return the successful OAuth token
            return view('popup.googledrive-oauth', compact('oauth'));

        } catch (Exception $e) {

            // An error was encountered
            return view('popup.googledrive-oauth', ['oauth' => null]);
        }
    }
}
