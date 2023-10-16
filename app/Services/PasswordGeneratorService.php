<?php

namespace App\Services;

use App\Services\Contracts\PasswordGeneratorContract;


class PasswordGeneratorService implements PasswordGeneratorContract
{
    private string $number;
    private string $upperCase;
    private string $lowerCase;

    private int $numbersCount;
    private int $bigLettersCount;
    private int $smallLettersCount;

    private bool $numbers;
    private bool $bigLetters;
    private bool $smallLetters;

    public function __construct () {
        $this->number = '23456789';
        $this->upperCase = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $this->lowerCase = 'abcdefghijkmnopqrstuvwxyz';

        $this->numbersCount = 0;
        $this->bigLettersCount = 0;
        $this->smallLettersCount = 0;

        $this->numbers = false;
        $this->bigLetters = false;
        $this->smallLetters = false;
    }

    public function generatePassword(bool $numbers, bool $bigLetters, bool $smallLetters, int $passwordLength): array
    {
        $parts = intval($numbers) + intval($bigLetters) + intval($smallLetters);
        $this->numbers = (bool)$numbers;
        $this->bigLetters = (bool)$bigLetters;
        $this->smallLetters = (bool)$smallLetters;

        self::getCounts($parts, $passwordLength);

        $result = self::validate($parts, $passwordLength);
        if ($result['isError']) return $result;

        $password = self::getPassword();

        return [
            'password' => str_shuffle($password),
            'isError' => false,
            'errorMessage' => '',
        ];
    }

    private function getPassword(): string {
        $password = '';
        $numberSymbols = '';
        if ($this->numbers) {
            while (strlen($numberSymbols) != $this->numbersCount) {
                $numberSymbols .= self::getRandom($this->number, 1);
                $numberSymbols = self::getUnique($numberSymbols);
            }
            $password .= $numberSymbols;
        }
        $smallLettersSymbols = '';
        if ($this->smallLetters) {
            while (strlen($smallLettersSymbols) != $this->smallLettersCount) {
                $smallLettersSymbols .= self::getRandom($this->lowerCase, 1);
                $smallLettersSymbols = self::getUnique($smallLettersSymbols);
            }
            $password .= $smallLettersSymbols;
        }
        $bigLettersSymbols = '';
        if ($this->bigLetters) {
            while (strlen($bigLettersSymbols) != $this->bigLettersCount) {
                $bigLettersSymbols .= self::getRandom($this->upperCase, 1);
                $bigLettersSymbols = self::getUnique($bigLettersSymbols);
            }
            $password .= $bigLettersSymbols;
        }

        return $password;
    }

    private function getCounts(int $parts, int $passwordLength):void
    {
        $numbersCount = 0;
        $bigLettersCount = 0;
        $smallLettersCount = 0;

        if ($parts === 3) {
            $numbersCount = floor($passwordLength / $parts);
            $bigLettersCount = floor($passwordLength / $parts);
            $smallLettersCount = $passwordLength - $numbersCount -$bigLettersCount;
        } elseif ($parts === 2) {
            if ($this->numbers) {
                $numbersCount = floor($passwordLength / $parts);
                if ($this->bigLetters) {
                    $bigLettersCount = $passwordLength - $numbersCount;
                } else {
                    $smallLettersCount = $passwordLength - $numbersCount;
                }
            }
            if ($this->bigLetters) {
                $bigLettersCount = floor($passwordLength / $parts);
                if ($this->numbers) {
                    $numbersCount = $passwordLength - $bigLettersCount;
                } else {
                    $smallLettersCount = $passwordLength - $bigLettersCount;
                }
            }
            if ($this->smallLetters) {
                $smallLettersCount = floor($passwordLength / $parts);
                if ($this->numbers) {
                    $numbersCount = $passwordLength - $smallLettersCount;
                } else {
                    $bigLettersCount = $passwordLength - $smallLettersCount;
                }
            }
        } else {
            if ($this->numbers) {
                $numbersCount = $passwordLength;
            }
            elseif ($this->bigLetters) {
                $bigLettersCount = $passwordLength;
            }
            else {
                $smallLettersCount = $passwordLength;
            }
        }

        $this->numbersCount = $numbersCount;
        $this->bigLettersCount = $bigLettersCount;
        $this->smallLettersCount = $smallLettersCount;
    }

    private function getRandom(string $set, int $length): string
    {
        $rand = '';
        $setLength = strlen($set);

        for ($i = 0; $i < $length; $i++)
        {
            $rand .= $set[random_int(0, $setLength - 1)];
        }

        return $rand;
    }

    private function getUnique(string $string): string
    {
        $array = str_split($string);
        $collection = collect($array);
        $unique = $collection->unique()->toArray();
        return implode('', $unique);
    }

    private function validate(int $parts, int $passwordLength): array
    {
        $result = [
            'password' => '',
            'isError' => false,
            'errorMessage' => '',
        ];
        $maxNumberCount = 1000;
        $maxBigLettersCount = 1000;
        $maxSmallLettersCount = 1000;

        if ($parts > $passwordLength) {
            $result['isError'] = true;
            $result['errorMessage'] = "Length of password is too short";
        } else {
            if ($this->numbersCount > 0 & $this->numbersCount > strlen($this->number)) {
                $maxNumberCount = strlen($this->number);
                $result['isError'] = true;
            }
            if ($this->bigLettersCount > 0 & $this->bigLettersCount > strlen($this->upperCase)) {
                $maxBigLettersCount = strlen($this->upperCase);
                $result['isError'] = true;
            }
            if ($this->smallLettersCount > 0 & $this->smallLettersCount > strlen($this->lowerCase)) {
                $maxSmallLettersCount = strlen($this->lowerCase);
                $result['isError'] = true;
            }

            $sets3 = $this->numbers & $this->bigLetters & $this->smallLetters;
            $sets2 = $this->bigLetters & $this->smallLetters & !$this->numbers;

            if ($result['isError'] & $sets3) {
                $result['errorMessage'] = "Length of password is too long to create a password with unique symbols.";
            } elseif ($result['isError'] & $sets2) {
                $max = strlen($this->upperCase) + strlen($this->lowerCase);
                $result['errorMessage'] = "Max length of password is: $max";
            } elseif ($result['isError']) {
                $minPasswordLength = min($maxNumberCount, $maxBigLettersCount, $maxSmallLettersCount);
                $result['errorMessage'] = "Max length of password is: " . $minPasswordLength;
            }
        }

        return $result;
    }
}
