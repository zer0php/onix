<?php

declare(strict_types=1);

namespace Onix\Console;

class ArgumentsParser
{
    public function parse(array $argv): ArgumentCollection
    {
        $arguments = $this->parseArgumentsArray($argv);

        return new ArgumentCollection($arguments);
    }

    private function parseArgumentsArray(array $arguments): array
    {
        $parsedArguments = [];

        $i = -1;
        foreach ($arguments as $value) {
            if (preg_match('@^--(.+)=(.+)@', $value, $m)) {
                $parsedArguments[$i][$m[1]] = $m[2];
            } elseif (preg_match('@^--(.+)@', $value, $m)) {
                $parsedArguments[$i][$m[1]] = true;
            } elseif (preg_match('@^-(.+)=(.+)@', $value, $m)) {
                $parsedArguments[$i][$m[1]] = $m[2];
            } elseif (preg_match('@^-(.+)@', $value, $m)) {
                $parsedArguments[$i][$m[1]] = true;
            } else {
                $i++;
                $parsedArguments[$i][] = $value;
            }
        }

        return $parsedArguments;
    }
}
