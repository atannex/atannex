<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\Auth\StrongName;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

/**
 * Controller for handling user profile operations.
 */
class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     * Apply middleware for authentication, verification, and password confirmation.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'password.confirm']);
    }

    /**
     * Redirect route name for home.
     */
    protected const REDIRECT_HOME = 'home';

    /**
     * Route name for profile completion.
     */
    protected const PROFILE_COMPLETE_ROUTE = 'name.complete';

    /**
     * Display the user profile form.
     *
     * @param string $token The unique token for profile access
     * @return View The profile form view
     * @throws AuthorizationException
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
     * @throws ValidationException If validation fails
     * @throws Exception If saving the user fails
     */
    public function store(Request $request, string $token): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->name_token !== $token) {
            abort(403, 'Unauthorized access');
        }

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', new StrongName],
            ]);

            $user->name = Str::of($validated['name'])->trim()->toString();
            $user->name_token = null;

            $user->save();

            return redirect()
                ->route(self::REDIRECT_HOME)
                ->with('success', 'Profile completed successfully!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'An error occurred while updating your profile. Please try again.')
                ->withInput();
        }
    }
}
