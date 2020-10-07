<?php

namespace App\Http\Controllers;


use App\Models\User;
use Auth;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Str;

class ProfileController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('page.profile');
    }

    /**
     * Updates the user's profile information.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function update(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|string|between:2,255',
            'alias' => 'present|string|between:2,255|nullable',
            'date_of_birth' => 'present|date|date_format:Y-m-d|nullable',
            'biography' => 'present|string|between:0,4000|nullable',
            'photograph_checklist' => 'present|string|between:0,4000|nullable',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->fill($data);
        $user->saveOrFail();

        return redirect()->intended(route('profile'));
    }

    /**
     * Updates the user's password.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function updatePassword(Request $request)
    {
        $data = $this->validate($request, [
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|confirmed|min:8',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($data['old_password'], $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => 'The old password appears to be incorrect.',
            ]);
        }

        $user->password = Hash::make($data['new_password']);
        $user->setRememberToken(Str::random(60));
        $user->saveOrFail();
        event(new PasswordReset($user));
        Auth::login($user);

        return redirect()->intended(route('profile'))->with('status', 'Password successfully updated.');
    }
}
