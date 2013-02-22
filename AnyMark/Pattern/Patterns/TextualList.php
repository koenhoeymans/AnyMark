<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class TextualList extends Pattern
{
	public function getRegex()
	{
		// @todo make tab width variable
		return
			'@
			(
			(?<=^|\n)(?=[ ]{1,3}\S)		# indented 1-3 spaces
			|							# or
			(?<=^|\n\n|^\n)(?=\S)		# indented 0 spaces after blank line
			|							# or
			(?<=\S\n)(?=\t\S|[ ]{4}\S)	# indented tab after paragraph and no blank line
			)

			(?<list>
			(?<indentation>[ \t]*)			# indentation
			(
				(?<ul>[*+-])				# unordered list markers
				(?<a>[ \t]+)\S.*						# space and text
				(\n								# continuation of list: newline
				(\n\g{indentation}[*+-]?\g{a})?			# or two lines for paragraph
 
				.+)*
			|
				(?<ol>([0-9]+|\#)\.)		# ordered list markers
				(?<b>[ \t]+)\S.*					# space and text
				(\n								# continuation of list: newline
				(\n\g{indentation}(([0-9]+|\#)\.)?\g{b})?	# or two lines for paragraph
 
				.+)*
			)
			)

			(?=(?<blank_line_after>\n\n)|\n$|$)
			@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$listType = (isset($match['ol']) && ($match['ol'] !== '')) ? 'ol' : 'ul';

		# unindent
		$items = preg_replace(
			"@(\n|^)" . $match['indentation'] . "@", "\${1}", $match['list']
		);

		$list = $parent->createElement($listType);
		$list->append($parent->createText($items));

		return $list;
	}
}