<?php

namespace Alterindonesia\Procurex\Tests\Facades;

use Alterindonesia\Procurex\Facades\WordTemplate;
use Alterindonesia\Procurex\Tests\TestCase;
use Alterindonesia\Procurex\WordTemplateLinkData;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class WordTemplateTest extends TestCase
{

    /** @test */
    public function it_saves_as_successfully_and_does_not_throw_exception(): void
    {
        // Arrange
        $baseUrl = config('procurex.media_service_base_url');

        $templateUuid = '9ac27f9f-69ca-4074-8346-e18394e2aa1a';
        $path = '/tmp/procurex-word-template.docx';
        $expectedFilePath = __DIR__.DIRECTORY_SEPARATOR.'../fixtures/word-templates/generate-2-20231210-153010.docx';

        WordTemplate::fake([
            "$baseUrl/word-templates/$templateUuid/generate" => Http::response(file_get_contents($expectedFilePath))
        ]);

        // Act
        $options = WordTemplate::getOptions();

        WordTemplate::saveAs($templateUuid, $path);

        // Assert
        $this->assertEquals([], $options);
        $this->assertFileExists($path);
        $this->assertFileEquals($expectedFilePath, $path);

        File::delete($path);
    }


    /** @test */
    public function it_can_set_options_to_pdf(): void
    {
        // Act
        WordTemplate::toPdf();

        // Assert
        $this->assertEquals(['format' => 'pdf'], WordTemplate::getOptions());
    }


    /** @test */
    public function it_can_set_text_options(): void
    {
        // Act
        $variables1 = ['FIRSTTEXT' => 'PHPDocX'];
        $variables2 = ['MULTILINETEXT' => 'This is the first line.\nThis is the second line of text.'];
        $options2 = ['parseLineBreaks' => true];

        WordTemplate::toDocx()
            ->replaceVariableByText($variables1)
            ->replaceVariableByText($variables2, $options2);

        // Assert
        $expectedOptions = [
            'text' => [
                ['data' => $variables1],
                ['data' => $variables2, 'options' => $options2]
            ]
        ];
        $this->assertEquals($expectedOptions, WordTemplate::getOptions());
    }

    /** @test */
    public function it_can_set_tables_options(): void
    {
        // Act
        $tableData = [
            'data' => [
                [
                    'ITEM' => 'Product A',
                    'REFERENCE' => '107AW3',
                ],
                [
                    'ITEM' => 'Product B',
                    'REFERENCE' => '204RS67O',
                ],
                [
                    'ITEM' => 'Product C',
                    'REFERENCE' => '25GTR56',
                ]
            ],
        ];
        $tableOptions = ['target' => 'inline', 'parseLineBreaks' => true];

        WordTemplate::replaceTableVariable($tableData, $tableOptions);

        // Assert
        $expectedOptions = [
            'tables' => [
                ['data' => $tableData, 'options' => $tableOptions],
            ]
        ];
        $this->assertEquals($expectedOptions, WordTemplate::getOptions());
    }

    /** @test */
    public function it_can_set_lists_options(): void
    {
        // Act
        $listData = ['LISTVAR' => ['First item', 'Second item', 'Third item']];
        $listOptions = ['target' => 'inline', 'parseLineBreaks' => true];

        WordTemplate::replaceListVariable($listData, $listOptions);

        // Assert
        $expectedOptions = [
            'lists' => [
                ['data' => $listData, 'options' => $listOptions],
            ]
        ];

        $this->assertEquals($expectedOptions, WordTemplate::getOptions());
    }

    /** @test  */
    public function it_can_set_images_options(): void
    {
        // Act
        $filepath = __DIR__.DIRECTORY_SEPARATOR.'../fixtures/word-templates/file_example_JPG_100kB.jpg';
        $variables = ['HEADERIMG' => 'data:image/jpeg;base64,' .base64_encode(file_get_contents($filepath))];
        $options = ['height' => 3, 'width' => 3, 'target' => 'header'];

        WordTemplate::replaceImageVariable($variables, $options);

        // Assert
        $expectedOptions = [
            'images' => [
                ['data' => $variables, 'options' => $options],
            ]
        ];

        $this->assertEquals($expectedOptions, WordTemplate::getOptions());
    }

    /** @test */
    public function it_can_set_links_options(): void
    {
        // Act
        $variables = [
            'WORDFRAGMENT' => new WordTemplateLinkData('link to Google', 'http://www.google.com', [
                'color' => '0000FF',
                'u' => 'single',
            ]),
        ];
        $options = ['type' => 'inline'];

        WordTemplate::replaceLinkVariable($variables, $options);

        // Assert
        $expectedOptions = [
            'links' => [
                [
                    'data' => [
                        'WORDFRAGMENT' => [
                            'text' => $variables['WORDFRAGMENT']->text,
                            'options' => ['url' => $variables['WORDFRAGMENT']->url, ...$variables['WORDFRAGMENT']->options],
                        ]
                    ],
                    'options' => $options,
                ],
            ]
        ];

        $this->assertEquals($expectedOptions, WordTemplate::getOptions());
    }
}
