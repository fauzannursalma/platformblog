<?php

namespace App\Services\Auth;

use App\Enums\Auth\MessageEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthService {

    public static function register(string $name, string $email, string $password): User
    {
        $password = Hash::make($password);

        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);
    }

    public static function login(string $email, string $password): string
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [MessageEnum::FAIL_LOGIN->value],
            ]);
        }

        return $user->createToken('auth_token')->plainTextToken;
    }


    public static function getUser(User $user): User
    {
        return $user;
    }
}
