<?php

declare(strict_types=1);

namespace Onix\View;

class ViewBuilder
{
    private string $templatePath;

    public function __construct(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    public function buildView(string $template, array $variables = []): View
    {
        return new View(
            $this->templatePath . '/' . $template,
            $variables
        );
    }

    public function buildViewWithLayout(string $layout, string $template, array $variables = []): View
    {
        $variables['__template'] = $this->templatePath . '/' . $template;

        return $this->buildView($layout, $variables);
    }
}
