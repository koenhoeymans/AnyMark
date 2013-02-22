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
class DefinitionList extends Pattern
{
	public function getRegex()
	{
		return
			'@
			(?<=\n\n|^\n|^)
			(?<list>
				(
					[ ]{0,3}.+(\n[ ]{0,3}.+)*		# dt
					
					(\n\n?[ ]{0,3}:([ ]|\t)*.+		# dd
						(
							\n(?![ ]{0,3}:\s).+
							|
							\n\n([ ]{4}|\t).+
						)*
					)+
				)
				(
					\n\n?
					[ ]{0,3}.+(\n[ ]{0,3}.+)*		# dt
					
					(\n\n?[ ]{0,3}:([ ]|\t)*.+		# dd
						(
							\n(?![ ]{0,3}:\s).+
							|
							\n\n([ ]{4}|\t).+
						)*
					)+
				)*
			)
			@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$dl = $parent->createElement('dl');
		$dl->append($parent->createText($match['list']));

		return $dl;
	}
}