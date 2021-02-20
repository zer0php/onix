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
        $expression = $this->transformToXPathExpression($cssExpression, $contextNode);

        $domNodeList = $this->domXPath->query($expression, $contextNode);

        if ($domNodeList === false) {
            throw new InvalidArgumentException('Expression or Context Node are not valid');
        }

        return $domNodeList;
    }

    protected function transformToXPathExpression(string $cssExpression, ?DOMNode $contextNode): string
    {
        if ($cssExpression === '') {
            throw new InvalidArgumentException('Expression cannot be empty');
        }

        if (strpos($cssExpression, '//') === false) {
            if ($contextNode) {
                $cssExpression = './/' . $cssExpression;
            } else {
                $cssExpression = '//' . $cssExpression;
            }
        }

        if (!preg_match('#//[a-zA-Z0-9]+#', $cssExpression)) {
            $cssExpression = str_replace('//', '//*', $cssExpression);
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
