<?php

namespace AnyMark\Plugins\LinkDefinitionCollector;

class LinkDefinitionCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->plugin = new \AnyMark\Plugins\LinkDefinitionCollector\LinkDefinitionCollector();
    }

    /**
     * @test
     */
    public function aLinkDefinitionIsSquareBracketsWithDefinitionFollowedBySemicolonAndUrl()
    {
        $text = "\n[linkDefinition]: http://example.com\n";
        $this->plugin->process($text);

        $this->assertEquals(
            new \AnyMark\Pattern\Patterns\LinkDefinition(
                    'linkDefinition', 'http://example.com'
            ),
            $this->plugin->get('linkDefinition')
        );

        $this->assertNull($this->plugin->get('non existent definition'));
    }

    /**
     * @test
     */
    public function aTitleAttributeCanBeSpecifiedBetweenDoubleQuotes()
    {
        $text = "\n[linkDefinition]: http://example.com \"title\"\n";
        $this->plugin->process($text);

        $this->assertEquals(
            new \AnyMark\Pattern\Patterns\LinkDefinition(
                'linkDefinition', 'http://example.com', 'title'
            ),
            $this->plugin->get('linkDefinition')
        );
    }

    /**
     * @test
     */
    public function aTitleAttributeCanBeSpecifiedBetweenSingleQuotes()
    {
        $text = "\n[linkDefinition]: http://example.com 'title'\n";
        $this->plugin->process($text);

        $this->assertEquals(
            new \AnyMark\Pattern\Patterns\LinkDefinition(
                'linkDefinition', 'http://example.com', 'title'
            ),
            $this->plugin->get('linkDefinition')
        );
    }

    /**
     * @test
     */
    public function aTitleAttributeCanBeSpecifiedBetweenDoubleParentheses()
    {
        $text = "\n[linkDefinition]: http://example.com (title)\n";
        $this->plugin->process($text);

        $this->assertEquals(
            new \AnyMark\Pattern\Patterns\LinkDefinition(
                'linkDefinition', 'http://example.com', 'title'
            ),
            $this->plugin->get('linkDefinition')
        );
    }

    /**
     * @test
     */
    public function urlCanBePlacedBetweenAngleBrackets()
    {
        $text = "\n[linkDefinition]: <http://example.com>\n";
        $this->plugin->process($text);

        $this->assertEquals(
            new \AnyMark\Pattern\Patterns\LinkDefinition(
                'linkDefinition', 'http://example.com'
            ),
            $this->plugin->get('linkDefinition')
        );
    }

    /**
     * @test
     */
    public function aLinkDefinitionMustBePlacedOnItsOwnLine()
    {
        $text = "\ntext [linkDefinition]: http://example.com\n";
        $processedText = $this->plugin->process($text);

        $this->assertEquals(
            "\ntext [linkDefinition]: http://example.com\n",
            $processedText
        );
    }

    /**
     * @test
     */
    public function titleAttributeCanBePlacedOnNextLine()
    {
        $text = "\n[linkDefinition]: http://example.com\n'title'\n";
        $this->plugin->process($text);

        $this->assertEquals(
            new \AnyMark\Pattern\Patterns\LinkDefinition(
                    'linkDefinition', 'http://example.com', 'title'
            ),
            $this->plugin->get('linkDefinition')
        );
    }

    /**
     * @test
     */
    public function titleAttributeCanBePlacedIndentedOnNextLine()
    {
        $text = "\n[linkDefinition]: http://example.com\n\t'title'\n";
        $this->plugin->process($text);

        $this->assertEquals(
            new \AnyMark\Pattern\Patterns\LinkDefinition(
                    'linkDefinition', 'http://example.com', 'title'
            ),
            $this->plugin->get('linkDefinition')
        );
    }

    /**
     * @test
     */
    public function urlCanHaveSpaces()
    {
        $text = "\n[definition]: <url://with space>\n";
        $this->plugin->process($text);

        $this->assertEquals(
            new \AnyMark\Pattern\Patterns\LinkDefinition(
                'definition', 'url://with space'
            ),
            $this->plugin->get('definition')
        );
    }
}
