<?php

namespace AnyMark\Pattern\Patterns;

/**
 * @todo Tests would be more readable with:
 *
 *     $this->element('h1', 'header')->withAttr('id', 'header');
 *
 */
class HeaderTest extends \AnyMark\PatternReplacementAssertions
{
    public function setup()
    {
        $this->pattern = new \AnyMark\Pattern\Patterns\Header();
    }

    protected function getPattern()
    {
        return $this->pattern;
    }

    public function createHeader($level, $text)
    {
        $header = $this->elementTree()->createElement($level);
        $text = $this->elementTree()->createText($text);
        $header->append($text);

        return $header;
    }

    // ------------ Setext style ------------

    /**
     * @test
     */
    public function headerIsFollowedByLineOfAtLeastThreeCharacters()
    {
        $text = "\n\nheader\n---\n\n";
        $header = $this->createHeader('h1', 'header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function theLineOfAtLeastThreeCharactersMayNotBePrecededByABlankLine()
    {
        $text = "\n\nno header\n\n---\n\n";
        $this->assertEquals(null, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function noIndentationAllowedForSetext()
    {
        $text = "\n\n header\n---\n\n";
        $this->assertEquals(null, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function headerIsOptionallyPrecededByLineOfCharacters()
    {
        $text = "\n\n---\na header\n---\n\n";
        $header = $this->createHeader('h1', 'a header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function characterLinesCanBeMoreThanThreeCharacters()
    {
        $text = "\n\n-----\na header\n-----\n\n";
        $header = $this->createHeader('h1', 'a header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function onlyTheFirstThreeCharactersCount()
    {
        $text = "\n\na header\n---###\n\n";
        $header = $this->createHeader('h1', 'a header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function characterLinesCannotBeLessThanThreeCharacters()
    {
        $text = "\n\n--\nthis is no header\n--\n\n";
        $this->assertEquals(null, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function lineCharactersMayContainDashSigns()
    {
        $text = "\n\n---\na header\n---\n\n";
        $header = $this->createHeader('h1', 'a header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function lineCharactersMayContainEqualSigns()
    {
        $text = "\n\n===\na header\n===\n\n";
        $header = $this->createHeader('h1', 'a header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function lineCharactersMayContainPlusSigns()
    {
        $text = "\n\n+++\na header\n+++\n\n";
        $header = $this->createHeader('h1', 'a header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function lineCharactersMayContainStarSigns()
    {
        $text = "\n\n***\na header\n***\n\n";
        $header = $this->createHeader('h1', 'a header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function lineCharactersMayContainCaretSigns()
    {
        $text = "\n\n^^^\na header\n^^^\n\n";
        $header = $this->createHeader('h1', 'a header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function lineCharactersMayContainNumberSignSigns()
    {
        $text = "\n\n###\na header\n###\n\n";
        $header = $this->createHeader('h1', 'a header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function lineOfStartingAndEndingCharactersMustNotBeSame()
    {
        $text = "\n\n=-=\na header\n=-=\n\n";
        $header = $this->createHeader('h1', 'a header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function levelOfHeadersIsAssignedByOrderOfAppearance()
    {
        $text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
        $header = $this->createHeader('h1', 'first');

        $this->assertEquals($header, $this->applyPattern($text));

        $text = "\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
        $header = $this->createHeader('h2', 'second');

        $this->assertEquals($header, $this->applyPattern($text));

        $text = "\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
        $header = $this->createHeader('h3', 'third');

        $this->assertEquals($header, $this->applyPattern($text));

        $text = "\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
        $header = $this->createHeader('h4', 'fourth');

        $this->assertEquals($header, $this->applyPattern($text));

        $text = "\n\nfifth\n^^^\n\nsixth\n###\n\n";
        $header = $this->createHeader('h5', 'fifth');

        $this->assertEquals($header, $this->applyPattern($text));

        $text = "\n\nsixth\n###\n\n";
        $header = $this->createHeader('h6', 'sixth');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function levelOfHeadersIsRemembered()
    {
        $text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
        $header = $this->createHeader('h1', 'first');

        $this->assertEquals($header, $this->applyPattern($text));

        $text = "\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
        $header = $this->createHeader('h2', 'second');

        $this->assertEquals($header, $this->applyPattern($text));

        $text = "para\n\nother second\n===\n\npara";
        $header = $this->createHeader('h2', 'other second');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function headerCanBeStartOfDocument()
    {
        $text = "header\n---\n\n";
        $header = $this->createHeader('h1', 'header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function headerCanFollowStartPlusNewline()
    {
        $text = "\nheader\n---\n\n";
        $header = $this->createHeader('h1', 'header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function headerMustNotFollowABlankLine()
    {
        $text = "para\nheader\n---\n\n";
        $header = $this->createHeader('h1', 'header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function headerMustNotBeFollowedByBlankLine()
    {
        $text = "\n\nheader\n---\nparagraph\n\n";
        $header = $this->createHeader('h1', 'header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    // ------------ atx style ------------

    /**
     * @test
     */
    public function oneToSixHashesBeforeHeaderDeterminesHeaderLevel()
    {
        $text = "paragraph\n\n# level 1\n\nparagraph";
        $header = $this->createHeader('h1', 'level 1');

        $this->assertEquals($header, $this->applyPattern($text));

        $text = "paragraph\n\n## level 2\n\nparagraph";
        $header = $this->createHeader('h2', 'level 2');

        $this->assertEquals($header, $this->applyPattern($text));

        $text = "paragraph\n\n###### level 6\n\nparagraph";
        $header = $this->createHeader('h6', 'level 6');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function closingHashesAreOptional()
    {
        $text = "paragraph\n\n## level 2 #####\n\nparagraph";
        $header = $this->createHeader('h2', 'level 2');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function noIndentationAllowedForAtx()
    {
        $text = "paragraph\n\n ## level 2 #####\n\nparagraph";

        $this->assertEquals(null, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function headerMustNotBeFollowedByBlankLine2()
    {
        $text = "\n\n# header\nparagraph\n\n";
        $header = $this->createHeader('h1', 'header');

        $this->assertEquals($header, $this->applyPattern($text));
    }

    /**
     * @test
     *
     * Note difference with Setext style
     */
    public function headerDoesNotNeedBlankLineBefore()
    {
        $text = "paragraph\n# header\n\n";
        $header = $this->createHeader('h1', 'header');

        $this->assertEquals($header, $this->applyPattern($text));
    }
}
