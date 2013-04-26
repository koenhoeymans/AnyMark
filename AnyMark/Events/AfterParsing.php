<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Events;

use ElementTree\ElementTree;
use Epa\Event;

/**
 * @package AnyMark
 */
class AfterParsing implements Event
{
	private $tree;

	public function __construct(ElementTree $tree)
	{
		$this->tree = $tree;
	}

	public function getTree()
	{
		return $this->tree;
	}
}