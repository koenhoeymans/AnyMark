<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Events;

use Epa\Event;

/**
 * @package AnyMark
 */
class BeforeParsing implements Event
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