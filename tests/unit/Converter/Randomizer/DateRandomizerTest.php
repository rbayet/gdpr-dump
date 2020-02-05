<?php

declare(strict_types=1);

namespace Smile\GdprDump\Tests\Unit\Converter\Randomizer;

use DateTime;
use Smile\GdprDump\Converter\Randomizer\RandomizeDate;
use Smile\GdprDump\Tests\Unit\TestCase;

class DateRandomizerTest extends TestCase
{
    /**
     * Test the converter.
     */
    public function testConverter()
    {
        $converter = new RandomizeDate();

        $date = '1990-12-31';
        $randomizedDate = $converter->convert($date);
        $this->assertDateIsRandomized($randomizedDate, $date, 'Y-m-d');
    }

    /**
     * Test the converter with a custom date format.
     */
    public function testFormatParameter()
    {
        $format = 'd/m/Y';
        $converter = new RandomizeDate(['format' => $format]);

        $date = '31/12/1990';
        $randomizedDate = $converter->convert($date);
        $this->assertDateIsRandomized($randomizedDate, $date, $format);
    }

    /**
     * Test the converter with a custom min year.
     */
    public function testYearParameters()
    {
        $converter = new RandomizeDate(['min_year' => 1970, 'max_year' => 2020]);

        $date = '1990-12-31';
        $randomizedDate = $converter->convert($date);
        $this->assertDateIsRandomized($randomizedDate, $date, 'Y-m-d');
    }

    /**
     * Test if the current year is used if the min/max years are set to null.
     */
    public function testNullYears()
    {
        $converter = new RandomizeDate(['min_year' => null, 'max_year' => null]);

        $date = '1990-12-31';
        $randomizedDate = $converter->convert($date);

        $currentYear = (new DateTime())->format('Y');
        $randomizedYear = (new DateTime($randomizedDate))->format('Y');
        $this->assertSame($currentYear, $randomizedYear);
    }

    /**
     * Assert that an exception is thrown when the parameter "format" is empty.
     *
     * @expectedException \UnexpectedValueException
     */
    public function testEmptyFormat()
    {
        new RandomizeDate(['format' => '']);
    }

    /**
     * Assert that an exception is thrown when the min year is higher than the max year.
     *
     * @expectedException \Exception
     */
    public function testYearConflict()
    {
        $converter = new RandomizeDate(['min_year' => 2020, 'max_year' => 2019]);
        $converter->convert('1990-12-31');
    }

    /**
     * Assert that a date is randomized.
     *
     * @param string $randomized
     * @param string $actual
     * @param string $format
     */
    protected function assertDateIsRandomized(string $randomized, string $actual, string $format)
    {
        $randomizedDate = DateTime::createFromFormat($format, $randomized);
        $actualDate = DateTime::createFromFormat($format, $actual);

        $this->assertTrue($randomizedDate != $actualDate);
    }
}
