<?php

namespace App\Http\Controllers;


use App\Models\ProfileIcon;
use App\Models\ProfilePicture;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Image;
use Intervention\Image\Constraint;
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
            'active' => 'sometimes|boolean',
            'name' => 'required|string|between:2,255',
            'alias' => 'present|string|between:2,255|nullable',
            'date_of_birth' => 'present|date|date_format:Y-m-d|nullable',
            'biography' => 'present|string|between:0,4000|nullable',
            'photograph_checklist' => 'present|string|between:0,4000|nullable',
        ]);
        $data['active'] = array_key_exists('active', $data) && $data['active'] === '1';

        /** @var User $user */
        $user = Auth::user();
        $user->fill($data);
        $user->saveOrFail();

        return redirect()->back();
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

        return redirect()->back()->with('status', 'Password successfully updated.');
    }

    /**
     * Retrieves the user's profile icon and renders it. If no profile icon is found,
     * the default icon will be used.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function renderProfileIcon(User $user)
    {
        /** @var ProfileIcon $icon */
        $icon = $user->profileIcons()->first();
        if ($icon) {
            return Image::make($icon->image_data)->response();
        }
        return Image::make(resource_path('img/profile-icon.png'))->response();
    }

    /**
     * Updates the user's profile icon.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function uploadProfileIcon(Request $request)
    {
        // Validation
        $data = $this->validate($request, [
            'image' => 'required|image|max:1024',
        ]);

        // Create the image
        $image = Image::make($data['image']);
        $image->resize(64, 64, function(Constraint $constraint) {
            $constraint->aspectRatio();
        });

        // Save it to the database
        $icon = new ProfileIcon;
        $icon->image_data = $image->encode('png');
        $icon->user()->associate(Auth::user());
        $icon->saveOrFail();

        // Delete old profile icons
        ProfileIcon::where('user_id', '=', Auth::user()->id)
            ->where('id', '!=', $icon->id)
            ->whereNull('deleted_at')
            ->delete();

        // Return the response
        return redirect()->back()->with('status', 'Profile icon successfully updated.');
    }

    /**
     * Retrieves the user's profile picture and renders it. If no profile picture is found,
     * the default picture will be used.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function renderProfilePicture(User $user)
    {
        /** @var ProfilePicture $picture */
        $picture = $user->profilePictures()->first();
        if ($picture) {
            return Image::make($picture->image_data)->response();
        }
        return Image::make(resource_path('img/profile-picture.png'))->response();
    }

    /**
     * Updates the user's profile picture.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function uploadProfilePicture(Request $request)
    {
        // Validation
        $data = $this->validate($request, [
            'image' => 'required|image|max:4096',
        ]);

        // Create the image
        $image = Image::make($data['image']);
        $image->resize(960, 960, function(Constraint $constraint) {
            $constraint->aspectRatio();
        });

        // Save it to the database
        $picture = new ProfilePicture;
        $picture->image_data = $image->encode('png');
        $picture->user()->associate(Auth::user());
        $picture->saveOrFail();

        // Delete old profile icons
        ProfilePicture::where('user_id', '=', Auth::user()->id)
            ->where('id', '!=', $picture->id)
            ->whereNull('deleted_at')
            ->delete();

        // Return the response
        return redirect()->back()->with('status', 'Profile picture successfully updated.');
    }
}
