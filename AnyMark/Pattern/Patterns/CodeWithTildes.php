<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use AnyMark\ElementTree\ElementTree;
use AnyMark\ElementTree\Element;

/**
 * @package AnyMark
 */
class CodeWithTildes extends Code
{
	public function getRegex()
	{
		return '@
			(?<=\n\n)

			(\s*)~{3,}(?<attr>.+)?
			\n+
			(\\1\s*)
			((\n|.)+?)
			\n+
			\\1~{3,}

			(?=\n\n)
		@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$code = preg_replace("#\n$match[3](\s*.+)#", "\n\${1}", $match[4]);
		$code = $this->createCodeReplacement($code, true, $parent);

		$this->addAttributes($code, $match['attr']);

		return $code;
	}

	private function addAttributes(Element $code, $attrMatch)
	{
		$attributes = $this->getAttributes($attrMatch);

		foreach ($attributes as $attribute => $values)
		{
			if ($values === array())
			{
				continue;
			}
			$code->setAttribute($attribute, implode(' ', $values));
		}
	}

	private function getAttributes($attrMatch)
	{
		$attributes['id'] = $this->getIds($attrMatch);
		$attributes['class'] = $this->getClasses($attrMatch);

		preg_match_all(
			'@\s*\w+=(?:\"[^\"]*?\"|\'[^\']*?\'|[^\'\">\s]+)@', $attrMatch, $matches
		);

		foreach ($matches[0] as $match)
		{
			$match = trim($match);
			$attrName = strstr($match, '=', true);
			$attributes[$attrName][] = substr(strstr($match, '='), 2, -1);
		}

		return $attributes;
	}

	private function getIds($attrMatch)
	{
		preg_match_all('@(?<=[{\s])#.+?(?=\s|})@', $attrMatch, $matches);
		$ids = array();
		foreach ($matches[0] as $match)
		{
			$ids[] = substr($match, 1);
		}

		return $ids;
	}

	private function getClasses($attrMatch)
	{
		preg_match_all('@(?<=[{\s])\.?([^=\s#]+?)(?=\s|}|$)@', $attrMatch, $matches);

		$classes = array();
		foreach ($matches[1] as $key => $match)
		{
			if ($matches[0][$key][0] !== '.')
			{
				$match = 'language-' . $match;;
			}
			$classes[] = $match;
		}

		return $classes;
	}
}