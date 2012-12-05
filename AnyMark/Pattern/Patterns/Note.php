<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

/**
 * @package AnyMark
 */
class Note extends SpecialSection
{
	/**
	 * @param string $identifier
	 * @param string $elementName
	 */
	public function __construct()
	{
		parent::__construct('!note', 'div', 'note');
	}
}