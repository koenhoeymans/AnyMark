<?php

/**
 * @package AnyMark
 */
namespace AnyMark\ComponentTree;

/**
 * @package AnyMark
 */
abstract class Component
{
	protected $parent = null;

	protected $children = array();

	/**
	 * @return \AnyMark\ComponentTree\Element
	 */
	public function createElement($name)
	{
		return new Element($name);
	}

	/**
	 * @return \AnyMark\ComponentTree\Text
	 */
	public function createText($value)
	{
		return new Text($value);
	}

	/**
	 * @return \AnyMark\ComponentTree\Comment
	 */
	public function createComment($value)
	{
		return new Comment($value);
	}

	/**
	 * @return \AnyMark\ComponentTree\Component|null
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @return boolean
	 */
	public function hasChildren()
	{
		return !empty($this->children);
	}

	/**
	 * @return array:
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * Save contents of the component compatible with XML.
	 */
	abstract public function saveXmlStyle();

	/**
	 * Iterates through this component and its children recursively. Each
	 * is passed as a paramater into the callback.
	 * 
	 * @param callable $callback
	 */
	public function query(callable $callback)
	{
		$callback($this);
		foreach ($this->children as $child)
		{
			$child->query($callback);			
		}
	}
}