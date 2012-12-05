<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Processor;

/**
 * @package AnyMark
 */
interface DomProcessor
{
	public function process(\DOMDocument $domDocument);
}