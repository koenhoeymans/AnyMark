<?php

namespace AnyMark\Plugins\Detab;

class DetabTest extends \PHPUnit\Framework\TestCase
{
    public function setup()
    {
        $this->plugin = new \AnyMark\Plugins\Detab\Detab();
    }

    /**
     * @test
     */
    public function replacesTabBySpaces()
    {
        $this->assertEquals("para\n    \npara", $this->plugin->detab("para\n\t\npara"));
    }
}
