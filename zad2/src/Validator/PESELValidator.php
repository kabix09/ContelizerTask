<?php
declare(strict_types=1);

namespace Contelizer\Zad2\Validator;

class PESELValidator implements Validator
{
    const WEIGHTS = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];

    protected static function isLengthCorrect(string $input): bool
    {
        return strlen($input) === 11;
    }

    protected static function isDigit(string $input): bool
    {
        return is_numeric($input);
    }

    public static function getControlSum(string $pesel): int
    {
        $digits = str_split($pesel);

        return array_reduce(array_keys(self::WEIGHTS), function ($result, $index) use ($digits) {
            return $result + ($digits[$index] * self::WEIGHTS[$index]);
        }, 0);
    }

    public static function checkControlSum(string $input): bool
    {
        $checksum = self::getControlSum($input) % 10;

        $checksum = $checksum !== 0 ? 10 - $checksum : $checksum;

        return str_split($input)[10] == $checksum;
    }

    /**
     * @param string $input - PESEL code
     * @return bool
     */
    public static function validate(string $input): bool
    {
        return self::isLengthCorrect($input) && self::isDigit($input) && self::checkControlSum($input);
    }
}