<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Util;

/**
 * @package AnyMark
 */
interface FileExtensionProvider
{
	/**
	 * Add an extension to the resource (if any).
	 * 
	 * @param string $resource
	 */
	public function addExtension($resource);
}