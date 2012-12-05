<?php

/**
 * @package AnyMark
 */
namespace AnyMark;

use AnyMark\Parser\Parser;
use Fjor\Fjor;
use AnyMark\Processor\TextProcessor;
use AnyMark\Processor\DomProcessor;

/**
 * @package AnyMark
 */
class AnyMark implements Parser
{
	private $fjor;

	private $preTextProcessors = array();

	private $postDomProcessors = array();

	private $parser;

	/**
	 * Utility constructor method. Sets up the wiring of objects using Fjor.
	 */
	static public function setup()
	{
		$fjor = new \Fjor\Dsl\Dsl(new \Fjor\ObjectFactory\GenericObjectFactory());

		$fjor->given('Fjor\\Fjor')->thenUse($fjor);
		$fjor
			->given('AnyMark\\Util\\ContentRetriever')
			->thenUse('AnyMark\\Util\\DocFileRetriever');
		$fjor
			->given('AnyMark\\Util\\InternalUrlBuilder')
			->thenUse('AnyMark\\Util\\HtmlFileUrlBuilder');
		$fjor->given('AnyMark\\AnyMark')
			->andMethod('addPreTextProcessor')
			->addParam(array('AnyMark\\Processor\\Processors\\EmptyLineFixer'))
			->addParam(array('AnyMark\\Processor\\Processors\\NewLineStandardizer'))
			->addParam(array('AnyMark\\Processor\\Processors\\Detab'))
			->addParam(array('AnyMark\\Processor\\Processors\\LinkDefinitionCollector'));
		$fjor->given('AnyMark\\AnyMark')
			->andMethod('addPostDomProcessor')
			->addParam(array('AnyMark\\Processor\\Processors\\EmailObfuscator'));
		$fjor->given('AnyMark\\AnyMark')->constructWith(array($fjor));
		$fjor->setSingleton('AnyMark\\Processor\\Processors\\LinkDefinitionCollector');
		$fjor->setSingleton('AnyMark\\Pattern\\PatternList');
		$fjor->setSingleton('AnyMark\\Parser\\RecursiveReplacer');

		return $fjor->get('AnyMark\\AnyMark');
	}

	public function __construct(Fjor $fjor)
	{
		$this->fjor = $fjor;
	}

	private function getParser()
	{
		$patternList = $this->fjor->get('AnyMark\\Pattern\\PatternList');
		$this->parser = $this->fjor->get('AnyMark\\Parser\\RecursiveReplacer');
		$patternListFiller = new \AnyMark\Util\PatternListFiller($this->fjor);
		$ini = __DIR__ . DIRECTORY_SEPARATOR . 'Patterns.ini';
		$patternListFiller->fill($patternList, $ini);

		return $this->parser;
	}

	/**
	 * Change the wiring configuration through Fjor.
	 * 
	 * @return Fjor
	 */
	public function changeSetup()
	{
		return $this->fjor;
	}

	/**
	 * @param TextProcessor $processor
	 */
	public function addPreTextProcessor(TextProcessor $processor)
	{
		$this->preTextProcessors[] = $processor;
	}

	/**
	 * @param DomProcessor $domProcessor
	 */
	public function addPostDomProcessor(DomProcessor $domProcessor)
	{
		$this->postDomProcessors[] = $domProcessor;
	}

	/**
	 * Add Markdown text and get the parsed to HTML version back.
	 *  
	 * @see AnyMark\Parser.Parser::parse()
	 * @return string|\DomDocument
	 */
	public function parse($text)
	{
		# adding the \n for texts containing only a paragraph
		$text = $this->preProcess($text . "\n");

		$domDoc = $this->getParser()->parse($text);

		$this->postProcess($domDoc);

		$content = $domDoc->saveXML($domDoc->documentElement);
		# DomDocument::saveXml encodes entities like `&` when added within
		# a text node.
		$content = str_replace(
			array('&amp;amp;', '&amp;copy;', '&amp;quot;', '&amp;#'),
			array('&amp;', '&copy;', '&quot;', '&#'),
			$content
		);

		return $content;
	}

	private function preProcess($text)
	{
		foreach ($this->preTextProcessors as $processor)
		{
			$text = $processor->process($text);
		}
	
		return $text;
	}

	private function postProcess(\DOMDocument $document)
	{
		foreach ($this->postDomProcessors as $processor)
		{
			$processor->process($document);
		}
	}
}