<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Laravel\Commands;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase as TestCaseOriginal;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Tester\CommandTester;

abstract class TestCase extends TestCaseOriginal
{
    protected Container $container;
    protected CommandTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new TestApplicationContainer();
        Container::setInstance($this->container);
        Facade::setFacadeApplication($this->container);

        $loggerMock = $this->createMock(LoggerInterface::class);
        $this->container->instance('log', $loggerMock);
    }

    protected function tearDown(): void
    {
        Container::setInstance(null);
        Facade::clearResolvedInstances();

        if (class_exists(\Mockery::class)) {
            \Mockery::close();
        }
        parent::tearDown();
    }
}

class TestApplicationContainer extends Container
{
    public function runningUnitTests(): bool
    {
        return true;
    }
}
