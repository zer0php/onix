<?php

declare(strict_types=1);

namespace OnixTest\Unit\Crawler;

use DOMNode;
use DOMNodeList;
use DOMXPath;
use InvalidArgumentException;
use Onix\Crawler\DomCrawler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DomCrawlerTest extends TestCase
{
    /**
     * @test
     */
    public function find_GivenInvalidExpression_ThrowsException(): void
    {
        $xPathMock = $this->createMock(DOMXPath::class);
        $xPathMock->expects($this->once())
            ->method('query')
            ->willReturn(false);

        $domCrawler = new DomCrawler($xPathMock);

        $this->expectException(InvalidArgumentException::class);

        $domCrawler->find('invalid-expression');
    }

    /**
     * @test
     */
    public function find_GivenSimpleExpression_CallsDomXPathQueryWithTransformedExpression(): void
    {
        $expectedExpression = '//tag';
        $xPathMock = $this->getXPathMock($expectedExpression);

        $domCrawler = new DomCrawler($xPathMock);
        $domCrawler->find('tag');
    }

    /**
     * @test
     */
    public function find_GivenTagsSeparateMultipleSpaceExpression_CallsDomXPathQueryWithTransformedExpression(): void
    {
        $expectedExpression = '//tag1/tag2';
        $xPathMock = $this->getXPathMock($expectedExpression);

        $domCrawler = new DomCrawler($xPathMock);
        $domCrawler->find('tag1  tag2');
    }

    /**
     * @test
     */
    public function find_GivenTagsSeparateCommas_CallsDomXPathQueryWithTransformedExpression(): void
    {
        $expectedExpression = '//tag1|//tag2|//tag3';
        $xPathMock = $this->getXPathMock($expectedExpression);

        $domCrawler = new DomCrawler($xPathMock);
        $domCrawler->find('tag1,tag2,tag3');
    }

    /**
     * @test
     */
    public function find_GivenAttributeExpression_CallsDomXPathQueryWithTransformedExpression(): void
    {
        $expectedExpression = '//[@attr="dummy-attr"]';
        $xPathMock = $this->getXPathMock($expectedExpression);

        $domCrawler = new DomCrawler($xPathMock);
        $domCrawler->find('[attr="dummy-attr"]');
    }

    /**
     * @test
     */
    public function find_GivenIdExpression_CallsDomXPathQueryWithTransformedExpression(): void
    {
        $expectedExpression = '//[@id="Dummy-id_1"]';
        $xPathMock = $this->getXPathMock($expectedExpression);

        $domCrawler = new DomCrawler($xPathMock);
        $domCrawler->find('#Dummy-id_1');
    }

    /**
     * @test
     */
    public function find_GivenClassExpression_CallsDomXPathQueryWithTransformedExpression(): void
    {
        $expectedExpression = '//[contains(concat(" ", normalize-space(@class), " "), " Dummy-class_1 ")]';
        $xPathMock = $this->getXPathMock($expectedExpression);

        $domCrawler = new DomCrawler($xPathMock);
        $domCrawler->find('.Dummy-class_1');
    }

    /**
     * @test
     */
    public function find_GivenExpressionAndContextNode_CallsDomXPathQueryWithTransformerExpressionAndContextNode(): void
    {
        $contextNode = $this->createMock(DOMNode::class);

        $xPathMock = $this->createMock(DOMXPath::class);
        $xPathMock->expects($this->once())
            ->method('query')
            ->with('//dummy-tag', $contextNode)
            ->willReturn($this->createMock(DOMNodeList::class));

        $domCrawler = new DomCrawler($xPathMock);
        $domCrawler->find('dummy-tag', $contextNode);
    }

    private function getXPathMock(string $expression): MockObject
    {
        $xPathMock = $this->createMock(DOMXPath::class);
        $xPathMock->expects($this->once())
            ->method('query')
            ->with($expression)
            ->willReturn($this->createMock(DOMNodeList::class));

        return $xPathMock;
    }
}
