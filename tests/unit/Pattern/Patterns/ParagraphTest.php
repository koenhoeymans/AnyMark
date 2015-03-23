<?php

namespace Anymark;

class ParagraphTest extends PatternReplacementAssertions
{
    public function setup()
    {
        $this->pattern = new \AnyMark\Pattern\Patterns\Paragraph();
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function createP($text)
    {
        $p = $this->elementTree()->createElement('p');
        $text = $this->elementTree()->createText($text);
        $p->append($text);

        return $p;
    }

    /**
     * @test
     */
    public function emptyLineThenTextThenEmptyLineIsParagraph()
    {
        $text = "\n\nparagraph\n\n";
        $p = $this->createP('paragraph');

        $this->assertEquals($p, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function emptyLineThenTextThenLineBreakAndEndOfTextIsParagraph()
    {
        $text = "\n\nparagraph\n";
        $p = $this->createP('paragraph');

        $this->assertEquals($p, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function emptyLineThenTextThenEndOfTextIsParagraph()
    {
        $text = "\n\nparagraph";
        $p = $this->createP('paragraph');

        $this->assertEquals($p, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function canAlsoBeStartOfString()
    {
        $text = "paragraph\n\n";
        $p = $this->createP('paragraph');

        $this->assertEquals($p, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function cannotBeBothStartAndEndOfString()
    {
        $this->assertEquals(null, $this->applyPattern('paragraph'));
    }

    /**
     * @test
     */
    public function multipleParagraphsCanBePlacedAfterEachOther()
    {
        $text = "paragraph

another

yet another";
        $p = $this->createP('paragraph');

        $this->assertEquals($p, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function aParagraphCannotContainOnlyWhiteSpace()
    {
        $this->assertEquals(null, $this->applyPattern("\n\n \n\n"));
    }

    /**
     * @test
     */
    public function indentationOfThreeSpacesMaximum()
    {
        $text = "\n\n   paragraph\n\n";
        $p = $this->createP('   paragraph');

        $this->assertEquals($p, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function indentedMoreThanThreeSpacesIsNoParagraph()
    {
        $text = "\n\n    paragraph\n\n";
        $this->assertEquals(null, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function indentedATabIsNoParagraph()
    {
        $text = "\n\n\tparagraph\n\n";
        $this->assertEquals(null, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function linesAreNotUnindented()
    {
        $text = "

 paragraph
 paragraph continued

";
        $p = $this->createP(" paragraph\n paragraph continued");

        $this->assertEquals($p, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function firstLineIsNotUnindented()
    {
        $text = "

 paragraph
paragraph continued

";
        $p = $this->createP(" paragraph\nparagraph continued");

        $this->assertEquals($p, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function followingLinesAreNotUnindented()
    {
        $text = "

paragraph
 paragraph continued

";
        $p = $this->createP("paragraph\n paragraph continued");

        $this->assertEquals($p, $this->applyPattern($text));
    }
}
