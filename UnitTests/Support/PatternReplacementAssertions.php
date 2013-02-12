<?php

namespace AnyMark\UnitTests\Support;

abstract class PatternReplacementAssertions extends \PHPUnit_Framework_TestCase
{
	abstract protected function getPattern();

	public function applyPattern($text)
	{
		preg_match($this->getPattern()->getRegex(), $text, $match);
		if (empty($match))
		{
			return null;
		}
		$result = $this->getPattern()->handleMatch(
			$match, new \AnyMark\ComponentTree\Element('foo')
		);

		return $result;
	}
}