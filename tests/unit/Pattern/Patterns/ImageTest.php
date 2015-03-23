<?php

namespace Anymark;

class ImageTest extends PatternReplacementAssertions
{
    public function setup()
    {
        $this->linkDefinitions = $this->getMock(
            '\\AnyMark\\Plugins\\LinkDefinitionCollector\\LinkDefinitionCollector'
        );
        $this->image = new \AnyMark\Pattern\Patterns\Image($this->linkDefinitions);
    }

    public function getPattern()
    {
        return $this->image;
    }

    public function createImgDom($alt, $url, $title = null)
    {
        $img = $this->elementTree()->createElement('img');
        $img->setAttribute('alt', $alt);
        if ($title) {
            $img->setAttribute('title', $title);
        }
        $img->setAttribute('src', $url);

        return $img;
    }

    /**
     * @test
     *
     * an inline image starts with an exclamation mark and has alt text
     * between square brackets followed by the path to the image between
     */
    public function hasTheCorrectForm()
    {
        $text = "Image is ![alt text](http://example.com/image.jpg) in between.";
        $img = $this->createImgDom('alt text', 'http://example.com/image.jpg', null);
        $this->assertEquals($img, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function titleTextIsOptionalInSingleQuotes()
    {
        $text = "Image is ![alt text](http://example.com/image.jpg 'title text') in between.";
        $img = $this->createImgDom('alt text', 'http://example.com/image.jpg', 'title text');
        $this->assertEquals($img, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function titleTextIsOptionalInDoubleQuotes()
    {
        $text = "Image is ![alt text](http://example.com/image.jpg \"title text\") in between.";
        $img = $this->createImgDom('alt text', 'http://example.com/image.jpg', 'title text');
        $this->assertEquals($img, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function referenceStyleHasSameAltTextButWithLinkReferenceBetweenSquareBrackets()
    {
        $this->linkDefinitions
            ->expects($this->once())
            ->method('get')->with('id')
            ->will($this->returnValue(
                new \AnyMark\Pattern\Patterns\LinkDefinition('id', 'http://example.com/image.jpg')
            ));

        $text = "Image is ![alt text][id] in between.";
        $img = $this->createImgDom('alt text', 'http://example.com/image.jpg', null);
        $this->assertEquals($img, $this->applyPattern($text));
    }

    /**
     * @test
     */
    public function referenceStyleCanContainOptionalTitle()
    {
        $this->linkDefinitions
            ->expects($this->once())
            ->method('get')->with('id')
            ->will($this->returnValue(
                new \AnyMark\Pattern\Patterns\LinkDefinition('id', 'http://example.com/image.jpg', 'title')
            ));

        $text = "Image is ![alt text][id] in between.";
        $img = $this->createImgDom('alt text', 'http://example.com/image.jpg', 'title');
        $this->assertEquals($img, $this->applyPattern($text));
    }
}
