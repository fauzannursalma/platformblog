<?php

namespace App\Enums\Auth;

enum MessageEnum: string
{
    case SUCCESS_LOGIN = 'Logged in successfully.';
    case FAIL_LOGIN = 'Invalid credentials.';
    case SUCCESS_LOGOUT = 'Logged out successfully.';
    case FAIL_LOGOUT = 'Failed to log out.';
    case SUCCESS_REGISTER = 'Registered successfully.';
    case FAIL_REGISTER = 'Failed to register.';
    case SUCCESS_USER = 'User retrieved successfully.';
    case FAIL_USER = 'Failed to retrieve user.';
}
