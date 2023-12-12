<?php

namespace Alterindonesia\Procurex\Exceptions\WordTemplateFactory;

use Exception;

class WordTemplateNotFoundException extends WordTemplateException
{
    public function __construct()
    {
        parent::__construct('Word template not found. Check the code in Media Service');
    }
}
