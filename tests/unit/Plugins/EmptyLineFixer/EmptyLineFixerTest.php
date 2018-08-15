<?php

namespace AnyMark\Plugins\EmptyLineFixer;

class EmptyLineFixerTest extends \PHPUnit\Framework\TestCase
{
    public function setup()
    {
        $this->plugin = new \AnyMark\Plugins\EmptyLineFixer\EmptyLineFixer();
    }

    /**
     * @test
     */
    public function lineWithOnlySpacesAndOrTabsIsCleaned()
    {
        $this->assertEquals(
            "para\n\npara",
            $this->plugin->fix("para\n \t\npara")
        );
    }
}
