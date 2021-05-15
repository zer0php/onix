<?php

namespace Onix\Console;

use Onix\Console\Input\InputInterface;
use Onix\Console\Output\OutputInterface;

interface CommandInterface
{
    public function run(InputInterface $input, OutputInterface $output): int;
}
