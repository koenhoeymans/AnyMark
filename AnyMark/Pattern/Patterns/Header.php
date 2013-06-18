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
class Header extends Pattern
{
	private $headerList = array(
		1 => array('before' => null, 'after' => null),
		2 => array('before' => null, 'after' => null),
		3 => array('before' => null, 'after' => null),
		4 => array('before' => null, 'after' => null),
		5 => array('before' => null, 'after' => null),
		6 => array('before' => null, 'after' => null)
	);

	/**
	 * array($id => $number);
	 * 
	 * @var array
	 */
	private $ids;

	public function getRegex()
	{
		return
		'@
		(?<=^|\n)
		(?<setext>
			([ ]{0,3}(?<pre>[-=+*^#]{3,})\n)?
			[ ]{0,3}(?<text>\S.*)\n
			[ ]{0,3}(?<post>[-=+*^#]{3,})
		)
		(?=\n|$)

		|

		(?<=^|\n)
		(?<atx>(?J)
			[ ]{0,3}(?<level>[#]{1,6})[ ]?(?<text>[^\n]+?)([ ]?[#]*)
		)
		(?=\n|$)
		@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if (isset($match['atx']))
		{
			return $this->createAtxHeaders($match, $parent);
		}
		else
		{
			return $this->createSetextHeaders($match, $parent);
		}
	}

	private function createSetextHeaders(array $match, ElementTree $parent)
	{
		foreach ($this->headerList as $level => $header)
		{
			if ($header['after'] === null)
			{
				$this->headerList[$level]['before'] = substr($match['pre'], 0, 3);
				$this->headerList[$level]['after'] = substr($match['post'], 0, 3);
				break;
			}
			if ($header['before'] === substr($match['pre'], 0, 3)
				&& $header['after'] === substr($match['post'], 0, 3))
			{
				break;
			}
		}

		$h = $parent->createElement('h' . $level);
		$h->append($parent->createText($match['text']));

		return $h;
	}

	private function createAtxHeaders(array $match, ElementTree $parent)
	{	
		$level = strlen($match['level']);
		$level = ($level > 5) ? 6 : $level;

		$h = $parent->createElement('h' . $level);
		$h->append($parent->createText($match['text']));

		return $h;
	}
}