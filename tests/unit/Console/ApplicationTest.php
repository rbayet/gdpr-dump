<?php
declare(strict_types=1);

namespace Smile\GdprDump\Tests\Unit\Console;

use Smile\GdprDump\Console\Application;
use Smile\GdprDump\Tests\Unit\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * Test the console application.
     */
    public function testConsoleApplication()
    {
        $application = new Application();

        $this->assertSame(APP_ROOT . '/config', $application->getConfigPath());
        $this->assertSame(APP_ROOT . '/vendor', $application->getVendorPath());
        $this->assertSame(Application::VERSION, $application->getVersion());
    }
}