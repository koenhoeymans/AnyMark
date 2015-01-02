<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Events;

use Epa\Api\Event;
use AnyMark\PublicApi\BeforeParsingEvent;

/**
 * @package AnyMark
 */
class BeforeParsing implements Event, BeforeParsingEvent
{
	private $text;

	public function __construct($text)
	{
		$this->text = $text;
	}

	public function getText()
	{
		return $this->text;
	}

	public function setText($text)
	{
		$this->text = $text;
	}
}