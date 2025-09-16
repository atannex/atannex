<?php

namespace App\Http\Controllers\Auth;

use App\Events\Users\UserCreated;
use App\Models\User;
use App\Rules\Auth\StrongName;
use App\Rules\Auth\StrongEmail;
use App\Rules\Auth\StrongPassword;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users when the intended URL fails.
     *
     * @return string
     */
    public function redirectTo()
    {
        return route('home');
    }

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', new StrongName],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new StrongEmail],
            'password' => ['required', 'string', 'min:8', 'confirmed', new StrongPassword],
            'terms' => ['accepted'],
            'timezone' => ['required', 'string'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'timezone' => $data['timezone'],
        ]);

        event(new UserCreated($user));
        Log::info('UserRegistered event dispatched for user ID: ' . $user->id);

        return $user;
    }
}
