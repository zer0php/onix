<?php

declare(strict_types=1);

namespace OnixTest\Unit\View;

use Onix\View\TemplateNotFoundException;
use Onix\View\View;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    /**
     * @test
     */
    public function getContents_NonExistentFile_ThrowsTemplateNotFoundException(): void
    {
        $nonExistentTemplateFile = 'not-existent-file';
        $view = new View($nonExistentTemplateFile);

        $this->expectExceptionObject(new TemplateNotFoundException('Template not found: ' . $nonExistentTemplateFile));

        $view->getContents();
    }

    /**
     * @test
     */
    public function getContents_Perfect_ReturnsTemplateContents(): void
    {
        $template = __DIR__ . '/../../Asset/View/template.php';
        $variables = [
            'text' => 'Hello',
            'name' => 'World'
        ];
        $view = new View($template, $variables);

        $contents = $view->getContents();

        $this->assertEquals('Hello World', $contents);
    }

}
