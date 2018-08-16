<?php

namespace AnyMark\Plugins\EmailObfuscator;

class EmailObfuscatorTest extends \PHPUnit\Framework\TestCase
{
    public function setup()
    {
        $this->emailObfuscator = new \AnyMark\Plugins\EmailObfuscator\EmailObfuscator();
    }

    /**
     * @test
     */
    public function encodesEmail()
    {
        $tree = new \ElementTree\ElementTree();
        $element = new \ElementTree\Element('a');
        $tree->append($element);
        $text = new \ElementTree\Text('my email');
        $element->append($text);
        $element->setAttribute('href', 'mailto:me@example.com');

        $this->emailObfuscator->handleTree($tree);

        $expected = '<a href="&#109;&#97;&#105;&#108;&#x74;&#x6f;&#x3a;'
            . 'm&#101;&#64;&#101;&#x78;&#x61;&#x6d;&#x70;l&#101;&#46;&#99;'
            . '&#x6f;&#x6d;">&#x6d;&#121;&#32;&#x65;&#109;&#x61;&#105;&#108;</a>';
        $this->assertEquals($expected, $element->toString());
    }
}
