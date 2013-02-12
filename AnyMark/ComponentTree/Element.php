<?php

/**
 * @package AnyMark
 */
namespace AnyMark\ComponentTree;

/**
 * @package AnyMark
 */
class Element extends ComponentTree
{
	private $name;

	private $attributes = array();

	public function __construct($name)
	{
		$this->name = (string) $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;
	}

	/**
	 * @param unknown $name
	 * @return string
	 */
	public function getAttributeValue($name)
	{
		return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
	}

	/**
	 * @see \AnyMark\ComponentTree\ComponentTree::saveXmlStyle()
	 */
	public function saveXmlStyle()
	{
		$content = '';
		foreach ($this->children as $child)
		{
			$content .= $child->saveXmlStyle();
		}

		$xml = '<' . $this->name . $this->getAttributes();

		if ($content === '')
		{
			$xml .= ' />';
		}
		else
		{
			$xml .= '>' . $content . '</' . $this->name . '>';
		}

		return $xml;
	}

	private function getAttributes()
	{
		$attr = '';
		foreach ($this->attributes as $name => $value)
		{
			$attr .= ' '
				. $name
				. '='
				. '"'
				. htmlentities($value, ENT_COMPAT, 'UTF-8', false)
				. '"';
		}

		return $attr;
	}
}