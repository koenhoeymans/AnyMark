<?php

/**
 * @package AnyMark
 */
namespace AnyMark\PublicApi;

/**
 * This event is thrown before parsing starts. The event name is `BeforeParsingEvent`.
 * 
 * @package AnyMark
 * @eventname BeforeParsingEvent
 */
interface BeforeParsingEvent
{
	/**
	 * Get the text as it is before before being parsed.
	 * 
	 * @return string
	 */
	public function getText();

	/**
	 * Set the text that will be parsed.
	 * 
	 * @param string $text
	 * @return void
	 */
	public function setText($text);
}