<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

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

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);
		$dl = $ownerDocument->createElement('dl');
		$dl->appendChild($ownerDocument->createTextNode($match['list']));

		return $dl;
	}
}