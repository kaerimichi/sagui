<?php
declare(strict_types=1);

namespace Infrastructure\Service\Renderer;

class TwigRenderer
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var array
     */
    private $extensions;

    /**
     * TwigRenderer constructor.
     * @param array $paths
     * @param array $options
     * @param \Twig_Extension ...$extensions
     * @throws \Twig_Error_Loader
     */
    public function __construct(array $paths, array $options, \Twig_Extension ...$extensions)
    {
        $loader = new \Twig_Loader_Filesystem([current($paths)]);
        foreach ($paths as $namespace => $path) {
            $loader->addPath($path, $namespace);
        }

        $this->twig = new \Twig_Environment($loader, $options);
        $this->extensions = $extensions;
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render (string $template, array $data): string
    {
        $this->extensions[] = new GlobalsExtension([
            'template_path' => \dirname($this->twig->getLoader()->getSourceContext($template)->getPath())
        ]);
        foreach ($this->extensions as $extension) {
            $this->twig->addExtension($extension);
        }
        return $this->twig->render($template, $data);
    }
}