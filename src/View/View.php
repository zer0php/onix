<?php

declare(strict_types=1);

namespace Onix\View;

use Onix\Http\Stream\ResourceStream;
use Onix\Http\StreamInterface;

class View extends ResourceStream implements StreamInterface
{
    private string $template;
    private array $variables;

    public function __construct(string $template, array $variables = [])
    {
        $this->template = $template;
        $this->variables = $variables;

        parent::__construct($this->getResourceFromRenderedTemplate());
    }

    /**
     * @param string $data
     * @return false|resource
     */
    protected function getResourceFromRenderedTemplate()
    {
        $resource = fopen('php://memory', 'wb+');
        $data = $this->render($this->template);
        fwrite($resource, $data);
        rewind($resource);

        return $resource;
    }

    protected function render(string $template): string
    {
        if (!file_exists($template)) {
            throw new TemplateNotFoundException("Template not found: {$template}");
        }

        ob_start();

        extract($this->variables, EXTR_OVERWRITE);
        require $template;

        return ob_get_clean();
    }
}
