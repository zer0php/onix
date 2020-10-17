<?php

declare(strict_types=1);

namespace OnixTest\Unit\View;

use Onix\View\View;
use Onix\View\ViewBuilder;
use PHPUnit\Framework\TestCase;

class ViewBuilderTest extends TestCase
{
    private ViewBuilder $viewBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewBuilder = new ViewBuilder('/path');
    }

    /**
     * @test
     */
    public function buildView_WithoutVariables_ReturnsNewViewInstance(): void
    {
        $expectedView = new View('/path/template');

        $view = $this->viewBuilder->buildView('template');

        $this->assertEquals($expectedView, $view);
    }

    /**
     * @test
     */
    public function buildView_WithVariables_ReturnsNewViewInstance(): void
    {
        $variables = [
            'name' => 'test'
        ];
        $expectedView = new View('/path/template', $variables);

        $view = $this->viewBuilder->buildView('template', $variables);

        $this->assertEquals($expectedView, $view);
    }

    /**
     * @test
     */
    public function buildViewWithLayout_WithoutVariables_ReturnsNewViewInstance(): void
    {
        $expectedView = new View('/path/layout', [
            '__template' => '/path/template'
        ]);

        $view = $this->viewBuilder->buildViewWithLayout('layout', 'template');

        $this->assertEquals($expectedView, $view);
    }

    /**
     * @test
     */
    public function buildViewWithLayout_WithVariables_ReturnsNewViewInstance(): void
    {
        $expectedView = new View('/path/layout', [
            'name' => 'test',
            '__template' => '/path/template'
        ]);

        $view = $this->viewBuilder->buildViewWithLayout('layout', 'template', [
            'name' => 'test',
        ]);

        $this->assertEquals($expectedView, $view);
    }
}
