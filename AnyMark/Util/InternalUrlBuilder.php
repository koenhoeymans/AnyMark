<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Util;

interface InternalUrlBuilder
{
	/**
	 * Creates the link pointing to a resource relative to a given resource. Both
	 * should be internal to the project and be relative to the root of
	 * the project.
	 * 
	 * @param string $to
	 * @param string $from
	 */
	public function createRelativeLink($toResource, $from = null);
}