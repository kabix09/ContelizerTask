<?php
require_once 'vendor/autoload.php';

use Contelizer\Zad2\Validator\PESELValidator;
use Exception;

try {
    $inputPesel = $argv[1];

    if(PESELValidator::validate($inputPesel))
        print("Pesel {$inputPesel} is correct! :)");
    else
        print("Pesel {$inputPesel} is incorrect! :c");
}catch (Exception $e) {
    printf("%s", $e->getMessage());
}