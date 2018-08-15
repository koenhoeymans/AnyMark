<?php

namespace AnyMark\Plugins\EscapeRestorer;

use ElementTree\ElementTreeElement;

class EscapeRestorerTest extends \PHPUnit\Framework\TestCase
{
    public function setup()
    {
        $this->plugin = new \AnyMark\Plugins\EscapeRestorer\EscapeRestorer();
    }

    /**
     * @test
     */
    public function restoresEscapedInText()
    {
        $tree = new \ElementTree\ElementTree();
        $text = $tree->createText('foo \* bar');
        $tree->append($text);

        $this->plugin->restoreTree($tree);

        $this->assertEquals('foo * bar', $tree->toString());
    }

    /**
     * @test
     */
    public function restoresEscapedInAttributeValues()
    {
        $tree = new \ElementTree\ElementTree();
        $div = $tree->createElement('div');
        $div->setAttribute('id', 'foo \* bar');
        $tree->append($div);

        $this->plugin->restoreTree($tree);

        $this->assertEquals('<div id="foo * bar" />', $tree->toString());
    }

    /**
     * @test
     */
    public function doesntRestoreInCode()
    {
        $tree = new \ElementTree\ElementTree();
        $div = $tree->createElement('code');
        $text = $tree->createText('foo \* bar');
        $tree->append($div);
        $div->append($text);

        $this->plugin->restoreTree($tree);

        $this->assertEquals('<code>foo \* bar</code>', $tree->toString());
    }

    /**
     * @test
     */
    public function adjustsInlineHtmlMatches()
    {
        $a = new \ElementTree\ElementTreeElement('a');
        $pattern = new \AnyMark\Pattern\Patterns\ManualHtmlInline();
        $event = new \AnyMark\Events\ParsingPatternMatch($a, $pattern);

        $this->plugin->handlePatternMatch($event);

        $this->assertEquals('<a manual="true" />', $a->toString());
    }

    /**
     * @test
     */
    public function listensForBlockHtmlMatches()
    {
        $a = new \ElementTree\ElementTreeElement('a');
        $pattern = new \AnyMark\Pattern\Patterns\ManualHtmlBlock();
        $event = new \AnyMark\Events\ParsingPatternMatch($a, $pattern);

        $this->plugin->handlePatternMatch($event);

        $this->assertEquals('<a manual="true" />', $a->toString());
    }

    /**
     * @test
     */
    public function doesntAdjustEscapingWhenElementsHaveAttributeAddedByPluginButRemovesAttribute()
    {
        $tree = new \ElementTree\ElementTree();
        $div = $tree->createElement('div');
        $div->setAttribute('manual', 'true');
        $text = $tree->createText('foo \* bar');
        $tree->append($div);
        $div->append($text);

        $this->plugin->restoreTree($tree);

        $this->assertEquals('<div>foo \* bar</div>', $tree->toString());
    }
}
