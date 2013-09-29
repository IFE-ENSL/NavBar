<?php

namespace Ifensl\Bundle\NavBarBundle\Generator;

use Symfony\Bundle\TwigBundle\TwigEngine;

class CssMenuGenerator extends AbstractMenuGenerator
{
    protected $templating;

    /**
     * Constructor
     *
     * @param array $menu
     * @param TimedTwigEngine $templating
     */
    public function __construct($menu, TwigEngine $templating)
    {
        parent::__construct($menu);
        $this->templating = $templating;
    }

    /**
     * Get Template
     *
     * @return Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine
     */
    public function getTemplate()
    {
        return $this->templating;
    }

    /**
     * {inheritDoc}
     */
    public function generate()
    {
        return $this->getTemplate()->render(
            "IfenslNavBarBundle:Generator:menu.css.twig",
            array('menu' => $this->getMenu())
        );
    }

    /**
     * {inheritDoc}
     */
    public function getContentType()
    {
        return 'text/css';
    }
}
