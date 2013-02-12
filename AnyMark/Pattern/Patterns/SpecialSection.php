<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use AnyMark\ComponentTree\ComponentTree;

/**
 * @package AnyMark
 */
class SpecialSection extends Pattern
{
	protected $identifier;

	protected $elementName;

	protected $className;

	/**
	 * @param string $identifier
	 * @param string $elementName
	 */
	public function __construct($identifier, $elementName, $className = null)
	{
		$this->identifier = $identifier;
		$this->elementName = $elementName;
		$this->className = $className;
	}

	public function getRegex()
	{
		return
			'@
			(?<=^|\n\n)

			[ ]{0,3}
			' . $this->identifier . '
			\n(?<blank_line_before>\n?)
			(?<text>
				(?<indentation>([ ]{4}|\t)).+
				(
					(?s).*?
				)?
			)

			(?=\n\n(?!\g{indentation})|$)
			@ix';
	}

	public function handleMatch(
		array $match, ComponentTree $parent, Pattern $parentPattern = null
	) {
		$text = preg_replace("@(^|\n)" . $match['indentation'] . "@", "\${1}", $match['text']);

		if ($match['blank_line_before'] != '')
		{
			$text = $text . "\n\n";
		}

		$el = $parent->createElement($this->elementName);
		$el->append($parent->createText($text));

		if ($this->className)
		{
			$el->setAttribute('class', $this->className);
		}

		return $el;
	}
}