<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

/**
 * @package
 */
class DefinitionDescription extends Pattern
{
	/**
	 * Definition term
	 * :   Colon up to three spaces, definition after one
	 *     or more spaces.
	 * 
	 *     A blank line to create paragraphs. New paragraphs
	 *     indented (at least) four spaces or a tab.
	 * 
	 * Definition term
	 * :   Multiple descriptions also can contain paragraphs
	 *     or other markup.
	 * 
	 *     A paragraph is created by leaving a blank line before
	 *     as explained above.
	 * 
	 * :   Multiple descriptions are separated with a colon.
	 * 
	 * Definition term
	 * 
	 * :   Description with one sentence being a paragraph
	 *     by leaving a blank line before.
	 * 
	 * Term A
	 * Term B
	 * 
	 * :   Multiple terms can exist for one or more descriptions.
	 * 
	 *    
	 *    -> either between tags if single description
	 *    -> or between tildes (indented) if multiple
	 */
	public function getRegex()
	{
		return
			'@
			(?<=\n)
	
			(?<pre_colon_indent>[ ]{0,3})
			:
			(?<post_colon_indent>([ ]|\t)*)
	
			(?<description>
				.+
				(
					\n(?![ ]{0,3}:\s).+
					|
					\n\n([ ]{4}|\t).+
				)*?
			)
	
			(?=
				\n[ ]{0,3}:[ ]
				|
				\n\n[ ]{0,3}\S
				|
				(\n)*$
			)
			@x';
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);

		# unindent
		$contents = preg_replace(
			"@\n"
			. $match['pre_colon_indent']
			. "[ ]?"
			. $match['post_colon_indent'] . "@",
			"\n",
			$match['description']
		);

		$dd = $ownerDocument->createElement('dd');
		$dd->appendChild($ownerDocument->createTextNode($contents));

		return $dd;
	}
}