<?php

declare(strict_types=1);

namespace Onix\View;

use Onix\Http\Stream\ResourceStream;
use Onix\Http\Stream\StringStream;
use Onix\Http\StreamInterface;

class View extends StringStream implements StreamInterface
{
    public function __construct(string $template, array $variables = [])
    {
        parent::__construct($this->render($template, $variables));
    }

    protected function render(string $template, array $variables = []): string
    {
        if (!file_exists($template)) {
            throw new TemplateNotFoundException("Template not found: {$template}");
        }

        ob_start();

        extract($variables, EXTR_OVERWRITE);
        require $template;

        return ob_get_clean();
    }
}
