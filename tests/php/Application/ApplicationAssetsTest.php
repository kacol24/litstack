<?php

namespace Lit\Test;

use Ignite\Application\Application;
use Illuminate\Foundation\Application as LaravelApplication;
use PHPUnit\Framework\TestCase;

class ApplicationAssetsTest extends TestCase
{
    /** @test */
    public function test_style_can_be_added()
    {
        $application = new Application(new LaravelApplication);
        $this->assertInstanceOf(Application::class, $application->style('path'));
        $this->assertContains('path', $application->getStyles());
    }

    /** @test */
    public function test_script_can_be_added()
    {
        $application = new Application(new LaravelApplication);
        $this->assertInstanceOf(Application::class, $application->script('foo'));
        $this->assertContains('foo', $application->getScripts());
    }
}
