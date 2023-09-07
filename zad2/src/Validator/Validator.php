<?php
declare(strict_types=1);

namespace Contelizer\Zad2\Validator;

interface Validator
{
    public static function validate(string $input): bool;
}