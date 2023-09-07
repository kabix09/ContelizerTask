<?php
declare(strict_types=1);

use Contelizer\Zad2\Validator\PESELValidator;
use PHPUnit\Framework\TestCase;

/**
 * Page to generate PESEL numbers:
 * https://pesel.cstudios.pl/o-generatorze/generator-on-line
 */
class PESELValidatorTest extends TestCase
{
    public function testValidControlSum()
    {
        $pesel = '50122193159';
        $controlSum = 141;

        $result = PESELValidator::getControlSum($pesel);

        $this->assertSame($controlSum, $result);
    }

    public function testInvalidControlSum()
    {
        $pesel = '88081512346';
        $controlSum = 162;  // correct one is 160 (but pesel still will be incorrect)

        $result = PESELValidator::getControlSum($pesel);

        $this->assertNotSame($controlSum, $result);
    }

    /**
     * Function that checks whether a valid PESEL number has been correctly detected
     *
     * Page to verify result:
     *  https://kalkulatory.gofin.pl/kalkulatory/sprawdzanie-pesel-weryfikacja-pesel
     *
     * @return void
     */
    public function testValidPesel() {
        $validPesel = '57021813472';

        $result = PeselValidator::validate($validPesel);
        $this->assertTrue($result);
    }

    /**
     * Function that checks whether a invalid PESEL number has been correctly detected
     *
     * Page to verify result:
     *  https://kalkulatory.gofin.pl/kalkulatory/sprawdzanie-pesel-weryfikacja-pesel
     *
     * @return void
     */
    public function testInvalidPesel() {
        $invalidPesel = '12345678901';

        $result = PeselValidator::validate($invalidPesel);
        $this->assertFalse($result);
    }
}
