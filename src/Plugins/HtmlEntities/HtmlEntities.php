<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\HtmlEntities;

use ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class HtmlEntities
{
	public function handleTree(ElementTree $tree)
	{
		$query = $tree->createQuery($tree);

		$allText = $query->find($query->allText());
		foreach ($allText as $text)
		{
			$value = preg_replace_callback(
				'@([^\\\\]+)([\\\\]+.)?@',
				function($match)
				{ 
					$match[2] = isset($match[2]) ? $match[2] : '';
					return htmlspecialchars(
						$match[1], ENT_NOQUOTES, 'UTF-8', false
					) . $match[2];
				},
				$text->getValue()
			);
			$text->setValue($value);
		}

		$allAttr = $query->find($query->allAttributes());
		foreach ($allAttr as $attr)
		{
			$value = htmlspecialchars($attr->getValue(), ENT_COMPAT, 'UTF-8', false);
			$attr->setValue($value);
		}
	}
}