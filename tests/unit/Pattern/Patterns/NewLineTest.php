<?php

namespace Anymark;

class NewLineTest extends PatternReplacementAssertions
{
    public function setup()
    {
        $this->pattern = new \AnyMark\Pattern\Patterns\NewLine();
    }

    protected function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @test
     */
    public function doubleSpaceAtEndOfLineBecomesNewLine()
    {
        $text = "Some text before  \nand after double space";
        $br = $this->elementTree()->createElement('br');
        $this->assertEquals($br, $this->applyPattern($text));
    }
}
