<?php

namespace App\Http\Controllers;

use App\Models\User;
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
    /**
     * Redirect route name for home.
     */
    protected const REDIRECT_HOME = 'home';

    /**
     * Route name for profile completion.
     */
    protected const PROFILE_COMPLETE_ROUTE = 'name.complete';

    /**
     * Create a new controller instance.
     * Apply middleware for authentication, verification, and password confirmation.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'password.confirm']);
    }

    /**
     * Display the user profile form.
     *
     * @param string $token The unique token for profile access
     * @return View The profile form view
     */
    public function show(string $token): View
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->name_token !== $token) {
            abort(403, 'Unauthorized access');
        }

        return view('auth.names', ['user' => $user]);
    }

    /**
     * Store the updated user profile information.
     *
     * @param Request $request The HTTP request containing form data
     * @param string $token The unique token for profile access
     * @return RedirectResponse Redirect to home with success message
     */
    public function store(Request $request, string $token): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->name_token !== $token) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', new StrongName],
        ]);

        $user->name = Str::of($validated['name'])->trim()->toString();
        $user->name_token = null;
        $user->save();

        return redirect()
            ->route(self::REDIRECT_HOME)
            ->with('success', 'Profile completed successfully!');
    }
}
