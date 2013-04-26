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
	private $event;

	private $callback;

	public function registerForEvent($event, $callback)
	{
		$this->event = $event;
		$this->callback = $callback;
	}

	public function getEvent()
	{
		return $this->event;
	}

	public function getCallback()
	{
		return $this->callback;
	}
}