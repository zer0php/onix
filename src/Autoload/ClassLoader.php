<?php

declare(strict_types=1);

namespace Onix\Autoload;

class ClassLoader
{
    private array $psr4;

    public function __construct(array $psr4)
    {
        $this->psr4 = $psr4;
    }

    public function register(bool $prepend = false): bool
    {
        return spl_autoload_register([$this, 'loadClass'], true, $prepend);
    }

    public function unRegister(): bool
    {
        return spl_autoload_unregister([$this, 'loadClass']);
    }

    public function loadClass(string $class): bool
    {
        $namespaceParts = explode('\\', $class);
        $prefix = array_shift($namespaceParts);
        $file = implode('/', $namespaceParts) . '.php';

        if (isset($this->psr4[$prefix])) {
            $fileWithPrefix = $this->psr4[$prefix] . '/' . $file;
        } else {
            $fileWithPrefix = $prefix . '/' . $file;
        }

        if (!file_exists($fileWithPrefix)) {
            return false;
        }

        require_once $fileWithPrefix;

        return true;
    }
}
