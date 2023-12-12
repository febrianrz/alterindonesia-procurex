<?php

namespace Alterindonesia\Procurex\Exceptions\WordTemplateFactory;

use Exception;

class WordTemplateCodeNotSetException extends Exception
{
    public function __construct()
    {
        parent::__construct('Code is not set');
    }
}
