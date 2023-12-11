<?php

namespace Alterindonesia\Procurex\Factories;

use Alterindonesia\Procurex\WordTemplateLinkData;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;

/**
 * Class WordTemplateFactory
 */
class WordTemplateFactory
{
    protected array $options = [];

    public function __construct(
        protected Factory $factory,
        protected Filesystem $filesystem,
        protected string $baseUrl,
        protected string $accessToken,
    ) {
    }

    public function saveAs(string $templateUuid, string $path): void
    {
        $this->newPendingRequest()->sink($path)->post("/word-templates/$templateUuid/generate", $this->options);

        $this->options = [];
    }

    public function toDocx(): static
    {
        unset($this->options['format']);

        return $this;
    }

    public function toPdf(): static
    {
        $this->options['format'] = 'pdf';

        return $this;
    }

    /**
     * Replaces an array of variables by their values
     *
     * @param  array  $variables
     *  keys: variable names
     *  values: text we want to insert
     * @param  array|null  $options
     * 'target': document (default), header, footer, footnote, endnote, comment
     * 'firstMatch' (bool) if true it only replaces the first variable match. Default is set to false.
     * 'parseLineBreaks' (bool) if true (default is false) parses the line breaks to include them in the Word document
     * 'raw' (bool) if true (default is false) replaces the variable by a string regardless the variable scope (tag values, attributes...).
     *     Only allows to replace a variable by a plain string. Use with caution
     */
    public function replaceVariableByText(array $variables, ?array $options = null): static
    {
        $this->options['text'][] = $options
            ? ['data' => $variables, 'options' => $options]
            : ['data' => $variables];

        return $this;
    }

    /**
     * Do the actual substitution of the variables in a 'table set of rows'
     *
     * @param  array  $variables
     *   keys: variable names
     *   values: table data we want to insert
     * @param  array|null  $options
     * 'target': document (default), header, footer
     * 'firstMatch' (bool) if true it only replaces the first variable match. Default is set to false.
     * 'parseLineBreaks' (bool) if true (default is false) parses the line breaks to include them in the Word document
     * 'type' (string) inline or block (default); used by WordFragment values
     * 'addExtraSiblingNodes' (bool) if true (default is false) parses and adds nodes between placeholders that don't have placeholders
     */
    public function replaceTableVariable(array $variables, ?array $options = null): static
    {
        $this->options['tables'][] = $options
            ? ['data' => $variables, 'options' => $options]
            : ['data' => $variables];

        return $this;
    }

    /**
     * Replaces a single variable within a list by a list of items
     *
     * @param  array  $variables
     *    keys: variable names
     *    values: list data we want to insert
     * @param  array|null  $options
     * 'target': document (default), header, footer
     * 'firstMatch' (bool) if true it only replaces the first variable match. Default is set to false.
     * 'parseLineBreaks' (bool) if true (default is false) parses the line breaks to include them in the Word document
     * 'type' (string) inline (default) or block; used by WordFragment values
     */
    public function replaceListVariable(array $variables, ?array $options = null): static
    {
        $this->options['lists'][] = $options
            ? ['data' => $variables, 'options' => $options]
            : ['data' => $variables];

        return $this;
    }

    /**
     * @param  array  $variables
     *   keys: this variable uniquely identifies the image we want to replace
     *   values: base64 image
     * @param  array|null  $options
     * 'firstMatch' (bool) if true it only replaces the first variable match. Default is set to false.
     * 'target' (string) document, header, footer, footnote, endnote, comment
     * 'width' (mixed) the value in cm (float) or 'auto' (use image size), 0 to not change the previous size
     * 'height' (mixed) the value in cm (float) or 'auto' (use image size), 0 to not change the previous size
     * 'dpi' (int) dots per inch. This parameter is only taken into account if width or height are set to auto.
     * 'mime' (string) forces a mime (image/jpg, image/jpeg, image/png, image/gif)
     * 'replaceShapes' (bool): default as false. If true, replace images in shapes too
     * 'resourceMode' (bool) if true, uses src as image resource. The image resource is transformed to PNG automatically. Default as false
     * 'streamMode' (bool) if true, uses src path as stream. PHP 5.4 or greater needed to autodetect the mime type; otherwise set it using mime option. Default as false
     * If any of these formatting parameters is not set, the width and/or height of the placeholder image will be preserved
     */
    public function replaceImageVariable(array $variables, ?array $options = null): static
    {
        $this->options['images'][] = $options
            ? ['data' => $variables, 'options' => $options]
            : ['data' => $variables];

        return $this;
    }

    /**
     * Replaces an array of variables by links
     *
     * @param  array<string, WordTemplateLinkData>  $variables
     *   keys: variable names
     *   values: WordTemplateLinkData instance
     * @param  array|null  $options
     * 'target': document (default), header, footer, footnote, endnote or comment
     * 'firstMatch' (bool) if true it only replaces the first variable match. Default is set to false.
     * 'stylesReplacementType' (string) usePlaceholderStyles (keep placeholder styles, styles from the imported HTML are ignored), mixPlaceholderStyles (mix placeholder styles, placeholder styles overwrite HTML styles with the same name). Applies to the following styles: pPr, rPr
     * 'stylesReplacementTypeIgnore' (array) styles to be ignored from the imported WordFragment. Use with mixPlaceholderStyles
     * 'stylesReplacementTypeOverwrite' (bool) if true, overwrite the placeholder styles don't set in stylesReplacementTypeIgnore. Use with mixPlaceholderStyles. Default as false
     * 'type': inline (only replaces the variable), block (default, removes the variable and its containing paragraph), inline-block (available only in Premium licenses, replaces the variable keeping block elements and the placeholder styles)
     * @return $this
     */
    public function replaceLinkVariable(array $variables, ?array $options = null): static
    {
        $variables = array_map(static fn (WordTemplateLinkData $linkData) => [
            'text' => $linkData->text,
            'options' => ['url' => $linkData->url, ...$linkData->options],
        ], $variables);

        $this->options['links'][] = $options
            ? ['data' => $variables, 'options' => $options]
            : ['data' => $variables];

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Register a stub callable that will intercept requests and be able to return stub responses.
     *
     * @param  callable|array|null  $callback
     * @return $this
     */
    public function fake($callback = null)
    {
        return new static($this->factory->fake($callback), $this->filesystem, $this->baseUrl, $this->accessToken);
    }

    protected function newPendingRequest(): PendingRequest
    {
        /** @var PendingRequest $pendingRequest */
        $pendingRequest = $this->factory
            ->baseUrl($this->baseUrl)
            ->withToken($this->accessToken)
            ->asJson()
            ->timeout(60);

        return $pendingRequest;
    }
}