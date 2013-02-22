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
class ListItem extends Pattern
{
	protected $markers = "(\#\.|[*+#-]|[0-9]+\.)";

	public function getRegex()
	{
		// @todo make tab width variable
		// @todo marker indent was removed by list?
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

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
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

		return $li;
	}
}