<?php

/**
 * @package AnyMark
 */
namespace AnyMark\UnitTests\Support;

use Epa\EventMapper;

/**
 * @package AnyMark
 */
class EventMapperMock implements EventMapper
{
	private $events = array();

	public function registerForEvent($event, $callback)
	{
		$this->events[$event] = $callback;
	}

	public function getEvent()
	{
		reset($this->events);
		return key($this->events);
	}

	public function getCallback($event = null)
	{
		if (is_string($event))
		{
			return $this->events[$event];
		}
		return reset($this->events);
	}
}