<?php

namespace App\Services\Contracts;

interface PasswordGeneratorContract
{
    public function generatePassword(bool $numbers, bool $bigLetters, bool $smallLetters, int $passwordLength);
}
