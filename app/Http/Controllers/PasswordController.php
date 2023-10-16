<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PasswordGeneratorService;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function index(): View {
        $context = [
            'numbers' => true,
            'password' => null,
            'is_error' => false,
            'big_letters' => true,
            'small_letters' => true,
            'password_length' => null,
            'password_is_generated' => false,
        ];

        return view('password', $context);
    }

    public function generatePassword(Request $request, PasswordGeneratorService $generator):view {
        $validation_rules = ['password_length' => 'required|numeric|gt:0'];
        $validated = $request->validate($validation_rules);

        $numbers = $request->get('numbers', true);
        $bigLetters = $request->get('big_letters', true);
        $smallLetters = $request->get('small_letters', true);
        $passwordLength = $request->get('password_length', 5);

        $result = $generator->generatePassword($numbers, $bigLetters, $smallLetters, $passwordLength);
        $isError = $result['isError'];
        $password = $result['password'];
        $errorMessage = $result['errorMessage'];

        $context = [
            'numbers' => $numbers,
            'is_error' => $isError,
            'password' => $password,
            'big_letters' => $bigLetters,
            'password_is_generated' => !$isError,
            'error_message' => $errorMessage,
            'small_letters' => $smallLetters,
            'password_length' => $passwordLength,
        ];

        return view('password', $context);
    }
}
