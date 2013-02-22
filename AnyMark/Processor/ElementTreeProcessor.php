<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Processor;

use ElementTree\ElementTree;

/**
 * @package AnyMark
 */
interface ElementTreeProcessor
{
	public function process(ElementTree $componentTree);
}