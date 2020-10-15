<?php

declare(strict_types=1);

namespace Onix\View;

use Onix\Http\StreamInterface;

class View implements StreamInterface
{
    private string $template;
    private array $variables;

    public function __construct(string $template, array $variables = [])
    {
        $this->template = $template;
        $this->variables = $variables;
    }

    public function getContents(): string
    {
        return $this->render($this->template);
    }

    protected function render(string $template): string
    {
        if (!file_exists($template)) {
            throw new TemplateNotFoundException("Template not found: ${template}");
        }

        ob_start();

        extract($this->variables, EXTR_OVERWRITE);
        require $template;

        return ob_get_clean();
    }
}
