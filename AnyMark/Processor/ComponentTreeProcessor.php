<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Processor;

use AnyMark\ComponentTree\ComponentTree;

/**
 * @package AnyMark
 */
interface ComponentTreeProcessor
{
	public function process(ComponentTree $componentTree);
}