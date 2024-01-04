<?php

namespace Alterindonesia\Procurex\Tests\Support;

use Alterindonesia\Procurex\Support\MediaNumber;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MediaNumberTest extends TestCase
{
    /** @test */
    public function should_generate_a_media_number_with_all_required_parameters(): void
    {
        $result = MediaNumber::generate(123, 'A000', 'TM', 'BA', 'PP', '2022', 'Asia/Jakarta');

        $expectedResult = "123/PR/A/TM/BA/PP/2022";
        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function should_generate_a_media_number_with_given_number_and_company_code(): void
    {
        $result = MediaNumber::generate(456, 'B000', 'TM', 'BA', 'PP', '2022', 'Asia/Jakarta');

        $expectedResult = "456/PR/B/TM/BA/PP/2022";
        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function should_generate_a_media_number_with_given_number_company_module_category_subcategory(): void
    {
        $result = MediaNumber::generate(789, 'C000', 'TM', 'BA', 'PP', '2022', 'Asia/Jakarta');

        $expectedResult = "789/PR/C/TM/BA/PP/2022";
        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function should_generate_a_media_number_with_null_year(): void
    {
        $result = MediaNumber::generate(123, 'A000', 'TM', 'BA', 'PP', null, 'Asia/Jakarta');

        $expectedYear = date('Y');
        $expectedResult = "123/PR/A/TM/BA/PP/{$expectedYear}";
        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function should_generate_a_media_number_with_null_timezone(): void
    {
        $tz = null;

        $result = MediaNumber::generate(456, 'B000', 'TM', 'BA', 'PP', '2022', $tz);

        $expectedResult = "456/PR/B/TM/BA/PP/2022";
        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function should_throw_exception_when_generate_a_media_number_with_empty_company_code(): void
    {
        $this->expectException(InvalidArgumentException::class);

        MediaNumber::generate(789, '', 'TM', 'BA', 'PP', '2022', 'Asia/Jakarta');
    }
}
