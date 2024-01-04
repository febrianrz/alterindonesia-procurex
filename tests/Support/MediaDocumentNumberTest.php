<?php

namespace Alterindonesia\Procurex\Tests\Support;

use Alterindonesia\Procurex\Support\MediaDocumentNumber;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MediaDocumentNumberTest extends TestCase
{
    /** @test */
    public function should_generate_a_media_number_with_all_parameters(): void
    {
        $result = MediaDocumentNumber::format(123, 'A000', 'TM', 'BA', 'PP', '2022', '	Asia/Makassar');

        $expectedResult = "123/PR/A/TM/BA/PP/2022";
        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function should_generate_a_media_number_with_given_number_and_company_code(): void
    {
        $result = MediaDocumentNumber::format(456, 'B000', 'TM', 'BA', 'PP', '2022', 'Asia/Makassar');

        $expectedResult = "456/PR/B/TM/BA/PP/2022";
        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function should_generate_a_media_number_with_given_number_company_module_category_subcategory(): void
    {
        $result = MediaDocumentNumber::format(789, 'C000', 'TM', 'BA', 'PP', '2022');

        $expectedResult = "789/PR/C/TM/BA/PP/2022";
        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function should_generate_a_media_number_without_year(): void
    {
        $result = MediaDocumentNumber::format(123, 'A000', 'TM', 'BA', 'PP');

        $expectedYear = now('Asia/Jakarta')->format('Y');
        $expectedResult = "123/PR/A/TM/BA/PP/{$expectedYear}";
        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function should_throw_exception_when_generate_a_media_number_with_empty_number(): void
    {
        $result = MediaDocumentNumber::format('', 'A000', 'TM', 'BA', 'PP', '2022', 'Asia/Jakarta');

        $this->assertEquals("/PR/A/TM/BA/PP/2022", $result);
    }

    /** @test */
    public function should_throw_exception_when_generate_a_media_number_with_empty_company_code(): void
    {
        $this->expectException(InvalidArgumentException::class);

        MediaDocumentNumber::format(789, '', 'TM', 'BA', 'PP', '2022', 'Asia/Jakarta');
    }
}
