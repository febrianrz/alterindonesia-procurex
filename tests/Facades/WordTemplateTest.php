<?php

namespace Alterindonesia\Procurex\Tests\Facades;

use Alterindonesia\Procurex\Exceptions\WordTemplateFactory\WordTemplateCodeNotSetException;
use Alterindonesia\Procurex\Exceptions\WordTemplateFactory\WordTemplateNotFoundException;
use Alterindonesia\Procurex\Facades\WordTemplate;
use Alterindonesia\Procurex\Tests\TestCase;
use Alterindonesia\Procurex\WordTemplateLinkData;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class WordTemplateTest extends TestCase
{
    /** @test
     * @throws WordTemplateCodeNotSetException
     */
    public function it_can_save_generated_word_from_word_template(): void
    {
        // Arrange
        $baseUrl = config('procurex.media_service_base_url');

        $templateCode = 'EXAMPLE';
        $path = '/tmp/procurex-word-template.docx';
        $expectedFilePath = __DIR__.DIRECTORY_SEPARATOR.'../fixtures/word-templates/generate-2-20231210-153010.docx';

        WordTemplate::fake([
            "$baseUrl/word-templates/$templateCode/generate" => Http::response(file_get_contents($expectedFilePath))
        ]);

        // Act
        $options = WordTemplate::getOptions();

        WordTemplate::ofCode($templateCode)->saveAs($path);

        // Assert
        $this->assertEquals([], $options);
        $this->assertFileExists($path);
        $this->assertFileEquals($expectedFilePath, $path);

        File::delete($path);
    }

    /**
     * @test
     * @covers \Alterindonesia\Procurex\Factories\WordTemplateFactory::saveAs()
     */
    public function it_throw_exception_if_template_code_not_set_when_save_as(): void
    {
        // Arrange
        $this->expectException(WordTemplateCodeNotSetException::class);

        // Act
        WordTemplate::saveAs('/tmp/test.docx');
    }

    /**
     * @test
     * @covers \Alterindonesia\Procurex\Factories\WordTemplateFactory::saveAs()
     */
    public function it_throw_exception_if_template_code_not_exist_when_save_as(): void
    {
        // Arrange
        $this->expectException(WordTemplateNotFoundException::class);

        $baseUrl = config('procurex.media_service_base_url');
        $templateCode = 'NOT_FOUND';

        WordTemplate::fake([
            "$baseUrl/word-templates/$templateCode/generate" => Http::response([
                'meta'=> [
                    'message'=> 'Data not found.',
                    'code'=> 404,
                    'number'=> '',
                    'action'=> '',
                ],
                'data'=> [],
            ], 404)
        ]);

        // Act
        WordTemplate::ofCode($templateCode)->saveAs('/tmp/test.docx');
    }

    /**
     * @test
     * @throws WordTemplateCodeNotSetException
     */
    public function it_can_save_as_media_generated_word_from_word_template(): void
    {
        // Arrange
        $baseUrl = config('procurex.media_service_base_url');

        $templateCode = 'EXAMPLE';
        $expectedMedia = [
            'id' => 6,
            'url' => 'http://media.procurex.test/storage/mr_sr_editor/procurex-word-template.docx',
            'mime' => 'image/jpeg',
            'size_in_bytes' => 102117,
            'created_at' => '2023-02-17T16:54:39.000000Z',
            'updated_at' => '2023-02-17T16:54:39.000000Z',
            'type' => [
                'id' => 1,
                'name' => 'TEST',
                'mimes' => ['dox', 'pdf'],
                'created_at' => '2023-02-17T16:49:42.000000Z',
                'updated_at' => '2023-02-17T16:49:42.000000Z',
                'actions' => [
                    'edit' => 'http://media.procurex.test/api/media-type/1',
                    'delete' => 'http://media.procurex.test/api/media-type/1',
                ],
            ],
            'actions' => [
                'edit' => 'http://media.procurex.test/api/media/6',
                'delete' => 'http://media.procurex.test/api/media/6',
            ],
        ];

        WordTemplate::fake([
            "$baseUrl/word-templates/$templateCode/generate-as-media" => Http::response([
                'meta'=> [
                    'message'=> 'Successfully create data.',
                    'code'=> 201,
                ],
                'data'=> $expectedMedia,
            ])
        ]);

        // Act
        $options = WordTemplate::getOptions();

        $result = WordTemplate::ofCode($templateCode)->saveAsMedia(mediaTypeId: 1);

        // Assert
        $this->assertEquals([], $options);
        $this->assertEquals($expectedMedia, $result);
    }

    /**
     * @test
     * @covers \Alterindonesia\Procurex\Factories\WordTemplateFactory::saveAsMedia()
     */
    public function it_throw_exception_if_template_code_not_set_when_save_as_media(): void
    {
        // Arrange
        $this->expectException(WordTemplateCodeNotSetException::class);

        // Act
        WordTemplate::saveAsMedia(mediaTypeId: 1);
    }

    /**
     * @test
     * @covers \Alterindonesia\Procurex\Factories\WordTemplateFactory::saveAsMedia()
     */
    public function it_throw_exception_if_template_code_not_exist_when_save_as_media(): void
    {
        // Arrange
        $this->expectException(WordTemplateNotFoundException::class);

        $baseUrl = config('procurex.media_service_base_url');
        $templateCode = 'NOT_FOUND';

        WordTemplate::fake([
            "$baseUrl/word-templates/$templateCode/generate-as-media" => Http::response([
                'meta'=> [
                    'message'=> 'Data not found.',
                    'code'=> 404,
                    'number'=> '',
                    'action'=> '',
                ],
                'data'=> [],
            ], 404)
        ]);

        // Act
        WordTemplate::ofCode($templateCode)->saveAsMedia(mediaTypeId: 1);
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

    /** @test  */
    public function it_can_set_qr_codes_options(): void
    {
        // Act
        $variables = ['HEADERIMG' => 'qr_code_text_1'];
        $options = ['height' => 3, 'width' => 3, 'target' => 'header'];

        WordTemplate::replaceImageVariableWithQrCode($variables, $options);

        // Assert
        $expectedOptions = [
            'qr_codes' => [
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
