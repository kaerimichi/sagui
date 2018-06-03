<?php
declare(strict_types=1);

namespace Infrastructure\Service\Renderer;

class GlobalsExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var array
     */
    private $variables;

    /**
     * GlobalsExtension constructor.
     * @param array $variables
     */
    public function __construct(array $variables)
    {
        $this->variables = $variables;
    }

    public function getGlobals()
    {
        return $this->variables;
    }
}