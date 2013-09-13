<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins;

use ElementTree\Filter\HasParentElement;
use AnyMark\Pattern\Patterns\ManualHtmlBlock;
use AnyMark\Pattern\Patterns\ManualHtmlInline;
use AnyMark\Pattern\Pattern;
use AnyMark\PublicApi\PatternMatch;
use ElementTree\Component;
use ElementTree\ElementTree;
use AnyMark\PublicApi\AfterParsingEvent;
use Epa\EventMapper;
use Epa\Plugin;

/**
 * @package AnyMark
 */
class EscapeRestorer implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'AfterParsingEvent', function(AfterParsingEvent $event) {
				$this->restoreTree($event->getTree());
			}
		);
		$mapper->registerForEvent(
			'PatternMatch', function(PatternMatch $match) {
				$this->handlePatternMatch($match);
			}
		);
	}

	private function restoreTree(ElementTree $tree)
	{
		# restore escaped
		$q = $tree->createQuery();
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

	private function handlePatternMatch(PatternMatch $match)
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