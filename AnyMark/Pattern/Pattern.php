<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern;

use ElementTree\Composable;
use ElementTree\ElementTreeElement;
use ElementTree\ElementTreeText;
use ElementTree\ElementTreeComment;

/**
 * @package vidola
 * 
 * When a text matches its pattern it transforms it.
 */
abstract class Pattern
{
	/**
	 * @return string
	 */
	abstract public function getRegex();

	/**
	 * @param array $match
	 * @param ElementTree $parent
	 * @param Pattern $parentPattern
	 * @return \ElementTree\Component
	 */
	abstract public function handleMatch(
		array $match, Composable $parent, Pattern $parentPattern = null
	);

	/**
	 * @param string $name
	 * @return \ElementTree\ElementTreeElement
	 */
	public function createElement($name)
	{
		return new ElementTreeElement($name);
	}

	/** 
	 * @param string $text
	 * @return \ElementTree\ElementTreeText
	 */
	public function createText($text)
	{
		return new ElementTreeText($text);
	}

	/**
	 * @param string $text
	 * @return \ElementTree\ElementTreeComment
	 */
	public function createComment($text)
	{
		return new ElementTreeComment($text);
	}
}