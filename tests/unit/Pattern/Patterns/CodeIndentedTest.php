<?php

namespace AnyMark\Pattern\Patterns;


class CodeIndentedTest extends \AnyMark\PatternReplacementAssertions
{
    public function setup()
    {
        $this->pattern = new \AnyMark\Pattern\Patterns\CodeIndented();
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function createFromText($text)
    {
        $pre = $this->elementTree()->createElement('pre');
        $code = $this->elementTree()->createElement('code');
        $text = $this->elementTree()->createText($text);
        $pre->append($code);
        $code->append($text);

        return $pre;
    }

    /**
     * @test
     */
    public function indentedTextIsAlsoCode()
    {
        $text =
"paragraph

	code

paragraph";

        $this->assertEquals($this->createFromText("code\n"), $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function variableIndentationIsPossibleWithinCode()
    {
        $text =
"paragraph

		a
	b
		c

paragraph";

        $codeText =
"	a
b
	c
";

        $this->assertEquals(
            $this->createFromText($codeText), $this->applyPattern($text)
        );
    }

    /**
     * @test
     */
    public function onlyBlankLinesBeforeAndAfterInStringAreSufficient()
    {
        $text =
"

	code

";

        $this->assertEquals($this->createFromText("code\n"), $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function codeCanContainBlankLines()
    {
        $text =
"paragraph

	code

	continued

paragraph";

        $this->assertEquals(
            $this->createFromText("code\n\ncontinued\n"), $this->applyPattern($text)
        );
    }
}
