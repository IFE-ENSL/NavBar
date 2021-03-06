<?php

namespace Ifensl\Bundle\NavBarBundle\Generator;

class JsonMenuGenerator extends AbstractMenuGenerator
{
    /**
     * {inheritDoc}
     */
    public function generate()
    {
        return json_encode($this->getMenu());
    }

    /**
     * {inheritDoc}
     */
    public function getContentType()
    {
        return 'application/json';
    }
}
