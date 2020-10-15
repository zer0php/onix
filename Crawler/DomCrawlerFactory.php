<?php

declare(strict_types=1);

namespace Onix\Crawler;

use DOMDocument;
use DOMXPath;

class DomCrawlerFactory
{
    public function createFromDomXPath(DOMXPath $domXPath): DomCrawler
    {
        return new DomCrawler($domXPath);
    }

    public function createFromDomDocument(DOMDocument $domDocument): DomCrawler
    {
        $domXPath = new DOMXPath($domDocument);

        return $this->createFromDomXPath($domXPath);
    }

    public function createFromHtml(string $html, int $options = 0): DomCrawler
    {
        $domDocument = new DOMDocument();
        $domDocument->loadHTML($html, $options);

        return $this->createFromDomDocument($domDocument);
    }
}
