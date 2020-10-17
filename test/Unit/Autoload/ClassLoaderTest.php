<?php

declare(strict_types=1);

namespace OnixTest\Unit\Autoload;

use Onix\Autoload\ClassLoader;
use PHPUnit\Framework\TestCase;
use OnixTest\Asset\DummyClass;

class ClassLoaderTest extends TestCase
{
    private const NOT_EXISTING_CLASS = '\NotExistingNamespace\NotExistingClass';

    private ClassLoader $classLoader;

    protected function setUp(): void
    {
        $this->classLoader = new ClassLoader([]);
    }

    /**
     * @test
     */
    public function loadClass_GivenNotExistingClass_ReturnsFalse(): void
    {
        $this->assertFalse($this->classLoader->loadClass(self::NOT_EXISTING_CLASS));
    }

    /**
     * @test
     */
    public function loadClass_GivenExistingClassWithoutPsr4Configs_ReturnsFalse(): void
    {
        $this->assertFalse($this->classLoader->loadClass(DummyClass::class));
    }

    /**
     * @test
     */
    public function loadClass_GivenExistingClass_ReturnsTrue(): void
    {
        $classLoader = new ClassLoader([
            'OnixTest' => __DIR__ . '/../..'
        ]);

        $this->assertTrue($classLoader->loadClass(DummyClass::class));
    }

    /**
     * @test
     */
    public function register_ReturnsTrue(): void
    {
        $registered = $this->classLoader->register();

        $this->assertTrue($registered);

        $this->classLoader->unRegister();
    }

    /**
     * @test
     */
    public function register_PrependTrue_RegisteredToFirstAutoloadFunction(): void
    {
        $this->classLoader->register(true);

        $autoloadFunctions = spl_autoload_functions();
        $firstAutoloadFunction = current($autoloadFunctions);

        $this->assertEquals([$this->classLoader, 'loadClass'], $firstAutoloadFunction);

        $this->classLoader->unRegister();
    }

    /**
     * @test
     */
    public function register_PrependFalse_RegisteredToLastAutoloadFunction(): void
    {
        $this->classLoader->register(false);

        $autoloadFunctions = spl_autoload_functions();
        $lastAutoLoadFunction = end($autoloadFunctions);

        $this->assertEquals([$this->classLoader, 'loadClass'], $lastAutoLoadFunction);

        $this->classLoader->unRegister();
    }

    /**
     * @test
     */
    public function unregister_NotRegistered_ReturnsFalse(): void
    {
        $unRegistered = $this->classLoader->unRegister();

        $this->assertFalse($unRegistered);
    }

    /**
     * @test
     */
    public function unregister_Registered_ReturnsTrue(): void
    {
        $this->classLoader->register();

        $unRegistered = $this->classLoader->unRegister();

        $this->assertTrue($unRegistered);
    }

    /**
     * @test
     */
    public function unregister_Registered_NotRegisteredInAutoloadFunctions(): void
    {
        $this->classLoader->register();

        $this->classLoader->unRegister();

        $autoloadFunctions = spl_autoload_functions();
        $firstAutoLoadFunction = current($autoloadFunctions);

        $this->assertNotEquals([$this->classLoader, 'loadClass'], $firstAutoLoadFunction);
    }
}
