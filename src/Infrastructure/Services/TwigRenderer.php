<?php
declare(strict_types=1);

namespace Infrastructure\Services;

class TwigRenderer
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * TwigRenderer constructor.
     * @param array $paths
     * @param array $options
     */
    public function __construct(array $paths, array $options)
    {
        $loader = new \Twig_Loader_Filesystem($paths);
        $this->twig = new \Twig_Environment($loader, $options);
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render (string $template, array $data): string
    {
        return $this->twig->render($template, $data);
    }
}