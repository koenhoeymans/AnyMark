<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;
use ElementTree\Element;

/**
 * @package AnyMark
 */
class TextualList extends Pattern
{
	protected $markers = "(\#\.|[*+#-]|[0-9]+\.)";

	public function getRegex()
	{
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
			(?<indentation>[ \t]*)					# indentation

			(										# marker
				(?<ol>(?<ol_marker>([0-9]+|\#)\.))
				|
				(?<ul>(?<ul_marker>[*+-]))
			)

			(?<space_after_marker>[ \t]+)			# space after marker

			\S.*									# text

			(										# continuation of list
				\n.+									# -> on next line
				|
				\n\n\g{indentation}						# -> white line
														#	+ indent
					(' . $this->markers . ')?			#	+ marker (when new item, else paragraph in item)
						\g{space_after_marker}			#	+ space
 				.+										#	+ text
			)*
		)

			(?=\n\n|\n$|$)
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
		$this->createListItems($items, $list);

		return $list;
	}

	private function createListItems($items, Element $list)
	{
		preg_match_all($this->getItemRegex(), $items, $matches, PREG_SET_ORDER);
		foreach ($matches as $match)
		{
			$this->handleItemMatch($match, $list);
		}
	}

	public function getItemRegex()
	{
		return
		'@
			(?<=(?<para_before>\n\n)|^|\n)
	
			# the structure of the list item
			# ------------------------------
			(
			(?<marker_indent>[ ]{0,3})	# indentation of the list marker
			' . $this->markers . '		# markers
			(?<text_indent>[ ]{0,3}|\t|(?=\n))	# spaces/tabs
			)

			# the list item content
			# ---------------------
			(?<content>
			.*						# text of first line
				(						# optionally more lines
					\n							# continue on next line unindented
					(?!
						\g{marker_indent}
						' . $this->markers . '
					)
					.+
					|							# or indented
					\n\n\g{marker_indent}
						(?!' . $this->markers . ')
					.+
				)*
			)
			(?=(?<para_after>\n\n|\n$)?)
			@x';
	}

	public function handleItemMatch(array $match, ElementTree $parent)
	{
		$paragraph = (($match['para_before'] == "\n\n") || isset($match['para_after']))
			? "\n\n" : "";
		$content = preg_replace(
			"@\n" . $match['marker_indent'] . "[ ]" . $match['text_indent'] . "@",
			"\n",
			$match['content']
		);

		$li = $parent->createElement('li');

		if ($paragraph !== '' || $content !== '')
		{
			$li->append($parent->createText($content . $paragraph));
		}

		$parent->append($li);
	}
}