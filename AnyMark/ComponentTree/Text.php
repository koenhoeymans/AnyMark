<?php

/**
 * @package AnyMark
 */
namespace AnyMark\ComponentTree;

/**
 * @package AnyMark
 */
class Text extends Component
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

	/**
	 * @param string $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * @see \AnyMark\ComponentTree\Component::saveXmlStyle()
	 */
	public function saveXmlStyle()
	{
		return htmlentities($this->value, ENT_NOQUOTES, 'UTF-8', false);
	}
}