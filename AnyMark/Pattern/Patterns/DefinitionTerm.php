<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;

/**
 * @package
 */
class DefinitionTerm extends Pattern
{
	public function getRegex()
	{
		return
			'@
			(?<=\n\n|\n|^)

			(?<term>[ ]{0,3}[^:\n].+)
			
			(?=
			(\n[ ]{0,3}.+)*					# other dt
			(\n\n?[ ]{0,3}:([ ]|\t)*.+		# dd
				(
					\n(?![ ]{0,3}:\s).+
					|
					\n\n([ ]{4}|\t).+
				)*
			)+
			)
			@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$dt = $parent->createElement('dt');
		$dt->append($parent->createText($match['term']));

		return $dt;
	}
}