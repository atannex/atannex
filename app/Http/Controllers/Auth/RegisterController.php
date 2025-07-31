<?php

namespace App\Http\Controllers\Auth;

use App\Models\Posts\Fon;
use App\Models\User;
use App\Rules\Auth\StrongName;
use App\Rules\Auth\StrongEmail;
use App\Rules\Auth\StrongPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', new StrongName],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new StrongEmail],
            'password' => ['required', 'string', 'min:8', 'confirmed', new StrongPassword],
            'terms' => ['accepted'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
