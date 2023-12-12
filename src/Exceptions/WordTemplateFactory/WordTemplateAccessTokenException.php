<?php

namespace Alterindonesia\Procurex\Exceptions\WordTemplateFactory;

class WordTemplateAccessTokenException extends WordTemplateException
{
    public function __construct()
    {
        parent::__construct('Unauthorized to generate from word template, please check access_token in config/procurex.php');
    }
}
