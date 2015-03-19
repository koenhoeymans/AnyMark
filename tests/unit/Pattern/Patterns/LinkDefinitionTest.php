<?php

namespace AnyMark\Pattern\Patterns;

class LinkDefinitionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function aLinkDefinitionHasAName()
    {
        $linkDef = new \AnyMark\Pattern\Patterns\LinkDefinition('name', 'url');
        $this->assertEquals('name', $linkDef->getName());
    }

    /**
     * @test
     */
    public function aLinkDefinitionHasAUrl()
    {
        $linkDef = new \AnyMark\Pattern\Patterns\LinkDefinition('name', 'url');
        $this->assertEquals('url', $linkDef->getUrl());
    }

    /**
     * @test
     */
    public function aLinkDefinitionOptionallyHasATitle()
    {
        $linkDef = new \AnyMark\Pattern\Patterns\LinkDefinition('name', 'url', 'title');
        $this->assertEquals('title', $linkDef->getTitle());
    }
}
