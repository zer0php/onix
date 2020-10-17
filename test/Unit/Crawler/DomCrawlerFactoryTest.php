<?php

declare(strict_types=1);

namespace OnixTest\Unit\Crawler;

use DOMDocument;
use DOMXPath;
use Onix\Crawler\DomCrawler;
use Onix\Crawler\DomCrawlerFactory;
use PHPUnit\Framework\TestCase;

class DomCrawlerFactoryTest extends TestCase
{
    private DomCrawlerFactory $domCrawlerFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->domCrawlerFactory = new DomCrawlerFactory();
    }

    /**
     * @test
     */
    public function createFromDomXPath_ReturnsNewDomCrawlerInstance(): void
    {
        $domXPathMock = $this->createMock(DOMXPath::class);

        $crawler = $this->domCrawlerFactory->createFromDomXPath($domXPathMock);

        $this->assertInstanceOf(DomCrawler::class, $crawler);
    }

    /**
     * @test
     */
    public function createFromDomDocument_ReturnsNewDomCrawlerInstance(): void
    {
        $domDocumentMock = new DOMDocument('<b></b>');

        $crawler = $this->domCrawlerFactory->createFromDomDocument($domDocumentMock);

        $this->assertInstanceOf(DomCrawler::class, $crawler);
    }

    /**
     * @test
     */
    public function createFromHtml_ReturnsNewDomCrawlerInstance(): void
    {
        $html = '<b></b>';

        $crawler = $this->domCrawlerFactory->createFromHtml($html);

        $this->assertInstanceOf(DomCrawler::class, $crawler);
    }
}
