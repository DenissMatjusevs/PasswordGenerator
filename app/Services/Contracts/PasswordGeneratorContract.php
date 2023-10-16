<?php

namespace App\Services\Contracts;

interface PasswordGeneratorContract
{
    public function generatePassword($numbers, $bigLetters, $smallLetters, $passwordLength);
}
