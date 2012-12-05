<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Util;

/**
 * @package AnyMark
 */
class ExtensionlessUrlBuilder implements InternalUrlBuilder
{
	/**
	 * @todo take relative links into accout
	 * @see AnyMark\Util.InternalUrlBuilder::createLink()
	 */
	public function createRelativeLink($toResource, $from = null)
	{
		return $toResource;
	}
}