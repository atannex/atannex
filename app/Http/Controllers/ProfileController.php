<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserNameToken;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\Auth\StrongName;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

/**
 * Controller for handling user profile operations.
 */
class ProfileController extends Controller
{
    protected const REDIRECT_HOME = 'home';

    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'password.confirm']);
    }

    /**
     * Display the user profile form using a token.
     */
    public function show(string $token): View
    {
        $user = Auth::user();

        $tokenRecord = UserNameToken::where('user_id', $user->id)
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$tokenRecord) {
            abort(403, 'Unauthorized access or token expired.');
        }

        return view('auth.names', ['user' => $user, 'token' => $token]);
    }

    /**
     * Store the updated user profile information.
     */
    public function store(Request $request, string $token): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $tokenRecord = UserNameToken::where('user_id', $user->id)
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$tokenRecord) {
            abort(403, 'Unauthorized access or token expired.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', new StrongName],
        ]);

        $user->name = Str::of($validated['name'])->trim()->toString();
        $user->save();

        $tokenRecord->delete();

        return redirect()
            ->route(self::REDIRECT_HOME)
            ->with('success', 'Profile completed successfully!');
    }
}
