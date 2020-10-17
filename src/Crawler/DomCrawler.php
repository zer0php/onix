<?php

declare(strict_types=1);

namespace Onix\Crawler;

use DOMNode;
use DOMNodeList;
use DOMXPath;
use InvalidArgumentException;

class DomCrawler
{
    private DOMXPath $domXPath;

    public function __construct(DOMXPath $domXPath)
    {
        $this->domXPath = $domXPath;
    }

    /**
     * @param string $cssExpression
     * @param DOMNode|null $contextNode
     * @return DOMNodeList
     * @throws InvalidArgumentException
     */
    public function find(string $cssExpression, ?DOMNode $contextNode = null): DOMNodeList
    {
        $expression = $this->transformToXPathExpression($cssExpression);
        $domNodeList = $this->domXPath->query($expression, $contextNode);

        if ($domNodeList === false) {
            throw new InvalidArgumentException('Expression or Context Node are not valid');
        }

        return $domNodeList;
    }

    protected function transformToXPathExpression(string $cssExpression): string
    {
        if (strpos($cssExpression, '//') !== 0) {
            $cssExpression = '//' . $cssExpression;
        }

        return preg_replace(
            [
                '/\s+/',
                '/,/',
                '/\[/',
                '/#([a-zA-Z0-9_-]+)/',
                '/\.([a-zA-Z0-9_-]+)/',
            ],
            [
                '/',
                '|//',
                '[@',
                '[@id="$1"]',
                '[contains(concat(" ", normalize-space(@class), " "), " $1 ")]',
            ],
            $cssExpression
        );
    }
}
