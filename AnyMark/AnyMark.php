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

	private $customIni = false;

	private $preTextProcessors = array();

	private $postDomProcessors = array();

	private $parser;

	/**
	 * Sets up the wiring of objects using Fjor.
	 * 
	 * @return \Fjor\Dsl\Dsl
	 */
	static public function setup()
	{
		$fjor = new \Fjor\Dsl\Dsl(new \Fjor\ObjectFactory\GenericObjectFactory());

		$fjor->given('Fjor\\Fjor')->thenUse($fjor);
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
		$patternList = $fjor->get('AnyMark\\Pattern\\PatternList');
		$fjor
			->given('AnyMark\\Parser\\RecursiveReplacer')
			->constructWith(array($patternList));

		return $fjor;
	}

	public function __construct(Fjor $fjor)
	{
		$this->fjor = $fjor;
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
	 * Set a custom ini which specifies which patterns to load.
	 * 
	 * @param string $ini
	 */
	public function setPatternsIni($ini)
	{
		$this->customIni = $ini;
	}

	/**
	 * Add Markdown text and get the parsed to HTML version back.
	 *  
	 * @see AnyMark\Parser.Parser::parse()
	 * @return \DomDocument
	 */
	public function parse($text)
	{
		# adding the \n for texts containing only a paragraph
		$text = $this->preProcess($text . "\n");

		$domDoc = $this->getParser()->parse($text);

		$this->postProcess($domDoc);

		return $domDoc;
	}

	/**
	 * DomDocument::saveXml encodes entities like `&` when added within
	 * a text node. This function reverses the damage done.
	 * 
	 * @param \DomDocument $domDoc
	 * @return string
	 */
	public function saveXml(\DomDocument $domDoc)
	{
		$content = $domDoc->saveXML($domDoc->documentElement);

		return str_replace(
			array('&amp;amp;', '&amp;copy;', '&amp;quot;', '&amp;#'),
			array('&amp;', '&copy;', '&quot;', '&#'),
			$content
		);
	}

	private function getParser()
	{
		if ($this->parser)
		{
			return $this->parser;
		}

		$patternList = $this->fjor->get('AnyMark\\Pattern\\PatternList');
		$patternListFiller = new \AnyMark\Util\PatternListFiller($this->fjor);
		$ini = $this->customIni ?: __DIR__ . DIRECTORY_SEPARATOR . 'Patterns.ini';
		$patternListFiller->fill($patternList, $ini);
		$this->parser = $this->fjor->get('AnyMark\\Parser\\RecursiveReplacer');

		return $this->parser;
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