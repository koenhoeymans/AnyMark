<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Util;

/**
 * @package AnyMark
 */
interface ContentRetriever
{
	/**
	 * Retrieve the contents of a file by the projects internal name.
	 * 
	 * @param string $file
	 */
	public function retrieve($file);
}