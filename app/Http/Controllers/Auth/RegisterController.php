<?php

namespace App\Http\Controllers\Auth;

use App\Events\Users\UserCreated;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Users\UserNameToken;
use App\Rules\Auth\StrongEmail;
use App\Rules\Auth\StrongPassword;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = 'home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function redirectTo(): string
    {
        return route($this->redirectTo);
    }

    protected function validator(array $data): ValidatorContract
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new StrongEmail],
            'password' => ['required', 'string', 'min:8', 'confirmed', new StrongPassword],
            'terms' => ['accepted'],
            'timezone' => ['required', 'string', 'timezone'],
        ], [
            'email.unique' => 'The email address is already registered.',
            'terms.accepted' => 'You must accept the terms and conditions.',
            'timezone.timezone' => 'The selected timezone is invalid.',
        ]);
    }

    protected function create(array $data): User
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'timezone' => $data['timezone'],
        ]);

        $token = UserNameToken::create([
            'user_id' => $user->id,
            'token' => UserNameToken::generateToken(),
            'expires_at' => now()->addMinutes(60),
        ]);

        event(new UserCreated($user));

        Log::info('User registered successfully', [
            'id' => $user->id,
            'email' => $user->email,
            'timezone' => $user->timezone,
            'token' => $token->token,
        ]);

        return $user;
    }
}
