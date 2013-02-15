<?php

/**
 * @package AnyMark
 */
namespace AnyMark\ElementTree;

/**
 * @package AnyMark
 */
class Comment extends Component
{
	private $value;

	public function __construct($value)
	{
		$this->value = (string) $value;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * `<!-- comment -->`
	 * @see AnyMark\ElementTreeComponent::saveXmlStyle()
	 */
	public function saveXmlStyle()
	{
		return '<!--' . $this->value . '-->';
	}
}