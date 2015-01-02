<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Events;

use ElementTree\ElementTree;
use Epa\Api\Event;
use AnyMark\PublicApi\AfterParsingEvent;

/**
 * @package AnyMark
 */
class AfterParsing implements Event, AfterParsingEvent
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