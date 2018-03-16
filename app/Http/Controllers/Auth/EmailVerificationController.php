<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ConfirmEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{

    public function confirm($token)
    {
        $data = json_decode(base64_decode(decrypt($token)));

        $user = User::where('email', $data->email)->where('email_verification_token', $data->token)->firstOrFail();

        $user->email_verified = TRUE;

        $user->email_verification_token = NULL;

        $user->save();

        Auth::login($user);

        return redirect()->route('admin.dashboard')->with('success', 'Email confirmed');

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        return view('auth.passwords.resend-email-confirmation-code');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $data['email'])->first();

        if ($user->email_verified) {
            return redirect()->back()->withErrors(['email' => 'Email is already verified']);
        }

        $user->generateEmailConfirmationToken()->save();

        $user->notify(new ConfirmEmail());

        return back()->with('message', 'Please check your email for link');

    }


}
