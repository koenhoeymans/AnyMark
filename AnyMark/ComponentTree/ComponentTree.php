<?php

/**
 * @package AnyMark
 */
namespace AnyMark\ComponentTree;

/**
 * @package AnyMark
 */
class ComponentTree extends Component
{
	/**
	 * Append a Component as a child. Optionally specifying after which other
	 * child component.
	 * 
	 * @param Component $elementTree
	 * @param Component $after
	 */
	public function append(Component $elementTree, Component $after = null)
	{
		$elementTree->parent = $this;
		if ($after)
		{
			$key = array_search($after, $this->children);
			array_splice($this->children, $key+1, 0, array($elementTree));
		}
		else
		{
			$this->children[] = $elementTree;
		}
	}

	/**
	 * @param Component $elementTree
	 */
	public function remove(Component $elementTree)
	{
		$children = array();
		foreach ($this->children as $child)
		{
			if ($elementTree !== $child)
			{
				$children[] = $child;
			}
			else
			{
				$child->parent = null;
			}
		}
		$this->children = $children;
	}

	/**
	 * @param Component $newComponent
	 * @param Component $oldComponent
	 */
	public function replace(Component $newComponent, Component $oldComponent)
	{
		foreach ($this->children as $key => $child)
		{
			if ($child === $oldComponent)
			{
				$this->children[$key] = $newComponent;
				break;
			}
		}
	}

	/**
	 * @see \AnyMark\ComponentTree\Component::saveXmlStyle()
	 */
	public function saveXmlStyle()
	{
		$content = '';
		foreach ($this->children as $child)
		{
			$content .= $child->saveXmlStyle();
		}

		return $content;
	}
}