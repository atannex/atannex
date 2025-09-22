<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use App\Rules\Auth\StrongEmail;
use App\Events\Users\UserCreated;
use App\Rules\Auth\StrongPassword;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

/**
 * Controller for handling user registration.
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after successful registration or when the intended URL fails.
     *
     * @var string
     */
    protected $redirectTo = 'home';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the redirect route name after registration.
     *
     * @return string
     */
    protected function redirectTo(): string
    {
        return route($this->redirectTo);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data The input data to validate
     * @return ValidatorContract The configured validator instance
     */
    protected function validator(array $data): ValidatorContract
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new StrongEmail],
            'password' => ['required', 'string', 'min:8', new StrongPassword],
            'terms' => ['accepted'],
            'timezone' => ['required', 'string', 'timezone'],
        ], [
            'email.unique' => 'The email address is already registered.',
            'terms.accepted' => 'You must accept the terms and conditions.',
            'timezone.timezone' => 'The selected timezone is invalid.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data Validated registration data
     * @return User The newly created user instance
     * @throws Exception If user creation fails
     */
    protected function create(array $data): User
    {
        try {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'timezone' => $data['timezone'],
                'name_token' => Str::random(64),
                'slug' => null,
            ]);

            event(new UserCreated($user));
            Log::info('UserRegistered event dispatched for user ID: ' . $user->id, [
                'email' => $data['email'],
                'timezone' => $data['timezone'],
            ]);

            return $user;
        } catch (Exception $exception) {
            Log::error('User registration failed: ' . $exception->getMessage(), [
                'email' => $data['email'],
            ]);
            throw $exception;
        }
    }
}
