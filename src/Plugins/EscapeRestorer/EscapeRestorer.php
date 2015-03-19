<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\EscapeRestorer;

use AnyMark\PublicApi\PatternMatch;
use ElementTree\Filter\HasParentElement;
use AnyMark\Pattern\Patterns\ManualHtmlBlock;
use AnyMark\Pattern\Patterns\ManualHtmlInline;
use ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class EscapeRestorer
{
	public function restoreTree(ElementTree $tree)
	{
		# restore escaped
		$q = $tree->createQuery($tree);
		$parentElHasAttrManual = $q->not($q->withParentElement(
			$q->lOr(
				$q->withAttribute($q->withName('manual')),
				$q->withName('code')
			)
		));
		$matches = $q->find($q->lOr(
			$q->allText($parentElHasAttrManual),
			$q->allAttributes($parentElHasAttrManual)
		));

		foreach ($matches as $match)
		{
			$match->setValue($this->restoreText($match->getValue()));
		}

		# remove `manual` attributes
		$matches = $q->find($q->allElements($q->withAttribute($q->withName('manual'))));
		foreach ($matches as $match)
		{
			$match->removeAttribute('manual');
		}
	}

	private function restoreText($text)
	{
		return preg_replace(
			'@\\\\([\\\\`*_{}\[\]()>#+-.!])@', "\${1}", $text
		);
	}

	public function handlePatternMatch(PatternMatch $match)
	{
		if (!($match->getComponent() instanceof \ElementTree\Element))
		{
			return;
		}
		$pattern = $match->getPattern();
		if (!($pattern instanceof ManualHtmlInline) && !($pattern instanceof ManualHtmlBlock))
		{
			return;
		}

		$match->getComponent()->setAttribute('manual', 'true');
	}
}