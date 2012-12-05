<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns\TableOfContents;

use \AnyMark\Pattern\Patterns\Header;

/**
 * @package AnyMark
 */
class HeaderFinder
{
	private $header;

	public function __construct(Header $header)
	{
		$this->header = $header;
	}

	/**
	 * Creates array with arrays of headers in the order found in the text.
	 * 
	 * array(
	 * 	array('first title' => 'foo', 'level' => 1, 'id' => 'bar')
	 * );
	 * 
	 * @param string $text
	 * @return array An array with headers, keys are 'title', 'level' and 'id'.
	 */
	public function getHeadersSequentially($text)
	{
		$headers = array();

		preg_match_all(
			$this->header->getRegex(),
			$text,
			$headerMatches,
			PREG_SET_ORDER
		);

		foreach ($headerMatches as $headerMatch)
		{
			$headerNode = $this->header->handleMatch($headerMatch, new \DOMDocument());
			$headers[] = array(
				'title' => $headerNode->nodeValue,
				'level' => substr($headerNode->nodeName, 1),
				'id'	=> $headerNode->getAttribute('id')
			);
		}

		return $headers;
	}
}