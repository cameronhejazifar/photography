<?php

namespace App\Http\Controllers;


use App\Models\User;
use Auth;
use Illuminate\Http\Request;

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
}
