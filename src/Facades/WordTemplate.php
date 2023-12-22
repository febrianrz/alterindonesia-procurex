<?php

namespace Alterindonesia\Procurex\Facades;

use Alterindonesia\Procurex\Factories\WordTemplateFactory;
use Illuminate\Support\Facades\Facade;

/**
 * @see WordTemplateFactory::ofCode()
 * @method static WordTemplateFactory ofCode(string $code)
 *
 * @see WordTemplateFactory::saveAs
 * @method static void saveAs(string $path)
 *
 * @see WordTemplateFactory::saveAsMedia
 * @method static void saveAsMedia(int $mediaTypeId, string|null $disk = null)
 *
 * @see WordTemplateFactory::toDocx
 * @method static WordTemplateFactory toDocx()
 *
 * @see WordTemplateFactory::toPdf
 * @method static WordTemplateFactory toPdf()
 *
 * @see WordTemplateFactory::replaceVariableByText
 * @method static WordTemplateFactory replaceVariableByText(array $variables, array|null $options = null)
 *
 * @see WordTemplateFactory::replaceTableVariable
 * @method static WordTemplateFactory replaceTableVariable(array $variables, array|null $options = null)
 *
 * @see WordTemplateFactory::replaceListVariable
 * @method static WordTemplateFactory replaceListVariable(array $variables, array|null $options = null)
 *
 * @see WordTemplateFactory::replaceImageVariable
 * @method static WordTemplateFactory replaceImageVariable(array $variables, array|null $options = null)
 *
 * @see WordTemplateFactory::replaceLinkVariable
 * @method static WordTemplateFactory replaceLinkVariable(array $variables, array|null $options = null)
 *
 * @see WordTemplateFactory::replaceVariableByHTML()
 * @method static WordTemplateFactory replaceVariableByHTML(string $var, string $type = 'block', string $html = '<html><body></body></html>', ?array $options = [])
 */
class WordTemplate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return WordTemplateFactory::class;
    }

    /**
     * Register a stub callable that will intercept requests and be able to return stub responses.
     *
     * @param  \Closure|array  $callback
     * @return WordTemplateFactory
     */
    public static function fake($callback = null)
    {
        return tap(static::getFacadeRoot(), function ($fake) use ($callback) {
            static::swap($fake->fake($callback));
        });
    }
}