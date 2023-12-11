<?php

namespace Alterindonesia\Procurex\Facades;

use Alterindonesia\Procurex\Factories\WordTemplateFactory;
use Illuminate\Support\Facades\Facade;

/**
 * @see WordTemplateFactory::toPdf
 * @method static WordTemplateFactory toPdf()
 *
 * @see WordTemplateFactory::replaceImageVariable
 * @method static WordTemplateFactory replaceImageVariable(array $variables, array|null $options = null)
 *
 * @see WordTemplateFactory::saveAs
 * @method static void saveAs(string $templateUuid, string $path)
 *
 * @see WordTemplateFactory::replaceLinkVariable
 * @method static WordTemplateFactory replaceLinkVariable(array $variables, array|null $options = null)
 *
 * @see WordTemplateFactory::toDocx
 * @method static WordTemplateFactory toDocx()
 *
 * @see WordTemplateFactory::replaceVariableByText
 * @method static WordTemplateFactory replaceVariableByText(array $variables, array|null $options = null)
 *
 * @see WordTemplateFactory::replaceTableVariable
 * @method static WordTemplateFactory replaceTableVariable(array $variables, array|null $options = null)
 *
 * @see WordTemplateFactory::replaceListVariable
 * @method static WordTemplateFactory replaceListVariable(array $variables, array|null $options = null)
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

    public static function getOptions(): array
    {
        return static::getFacadeRoot()->getOptions();
    }
}