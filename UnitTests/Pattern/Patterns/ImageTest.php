<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_ImageTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->linkDefinitions = $this->getMock(
			'\\AnyMark\\Processor\\Processors\\LinkDefinitionCollector'
		);
		$this->image = new \AnyMark\Pattern\Patterns\Image($this->linkDefinitions);
	}

	public function getPattern()
	{
		return $this->image;
	}

	public function createImgDom($alt, $title = null, $url)
	{
		$img = new \AnyMark\ComponentTree\Element('img');
		$img->setAttribute('alt', $alt);
		if ($title)
		{
			$img->setAttribute('title', $title);
		}
		$img->setAttribute('src', $url);

		return $img;
	}

	/**
	 * @test
	 */
	public function anInlineImageStartsWithAnExclamationMarkAndHasAltTextBetweenSquareBracketsFollowedByPathToImgBetweenRoundBrackets()
	{
		$text = "Image is ![alt text](http://example.com/image.jpg) in between.";
		$img = $this->createImgDom('alt text', null, 'http://example.com/image.jpg');
		$this->assertEquals($img, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function titleTextIsOptionalInSingleQuotes()
	{
		$text = "Image is ![alt text](http://example.com/image.jpg 'title text') in between.";
		$img = $this->createImgDom('alt text', 'title text', 'http://example.com/image.jpg');
		$this->assertEquals($img, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function titleTextIsOptionalInDoubleQuotes()
	{
		$text = "Image is ![alt text](http://example.com/image.jpg \"title text\") in between.";
		$img = $this->createImgDom('alt text', 'title text', 'http://example.com/image.jpg');
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
				new \AnyMark\Pattern\Patterns\LinkDefinition('id', 'http://example.com/image.jpg')));

		$text = "Image is ![alt text][id] in between.";
		$img = $this->createImgDom('alt text', null, 'http://example.com/image.jpg');
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
				new \AnyMark\Pattern\Patterns\LinkDefinition('id', 'http://example.com/image.jpg', 'title')));

		$text = "Image is ![alt text][id] in between.";
		$img = $this->createImgDom('alt text', 'title', 'http://example.com/image.jpg');
		$this->assertEquals($img, $this->applyPattern($text));
	}
}