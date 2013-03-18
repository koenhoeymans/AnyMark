<?php

/**
 * @package AnyMark
 */
namespace AnyMark;

use AnyMark\Parser\Parser;
use Fjor\Fjor;
use AnyMark\Processor\TextProcessor;
use AnyMark\Processor\ElementTreeProcessor;
use ElementTree\Element;
use ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class AnyMark implements Parser
{
	private $fjor;

	private $customPatterns = false;

	private $preTextProcessors = array();

	private $postElementTreeProcessors = array();

	private $parser;

	/**
	 * Sets up the wiring of objects using Fjor.
	 * 
	 * @return \Fjor\Dsl\Dsl
	 */
	static public function defaultSetup()
	{
		$fjor = new \Fjor\Dsl\Dsl(new \Fjor\ObjectFactory\GenericObjectFactory());

		$fjor->given('Fjor\\Fjor')->thenUse($fjor);
		$fjor
			->given('AnyMark\\Util\\InternalUrlBuilder')
			->thenUse('AnyMark\\Util\\ExtensionlessUrlBuilder');
		$fjor->given('AnyMark\\AnyMark')
			->andMethod('addPreTextProcessor')
			->addParam(array('AnyMark\\Processor\\Processors\\EmptyLineFixer'))
			->addParam(array('AnyMark\\Processor\\Processors\\NewLineStandardizer'))
			->addParam(array('AnyMark\\Processor\\Processors\\Detab'))
			->addParam(array('AnyMark\\Processor\\Processors\\LinkDefinitionCollector'));
		$fjor->given('AnyMark\\AnyMark')
			->andMethod('addPostElementTreeProcessor')
			->addParam(array('AnyMark\\Processor\\Processors\\EmailObfuscator'));
		$fjor->setSingleton('AnyMark\\Processor\\Processors\\LinkDefinitionCollector');
		$fjor->setSingleton('AnyMark\\Pattern\\PatternList');
		$patternList = $fjor->get('AnyMark\\Pattern\\PatternList');
		$fjor
			->given('AnyMark\\Parser\\RecursiveReplacer')
			->constructWith(array($patternList));

		return $fjor;
	}

	/**
	 * Syntactic method to create an instance. Eg
	 * 
	 *     \AnyMark\AnyMark::createWith(\AnyMark\AnyMark::defaultWiring());
	 * 
	 * @param Fjor $wiring
	 * @return \AnyMark\AnyMark
	 */
	static public function createWith(Fjor $wiring)
	{
		return $wiring->get('\\AnyMark\\AnyMark');
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
	public function addPostElementTreeProcessor(ElementTreeProcessor $componentTreeProcessor)
	{
		$this->postElementTreeProcessors[] = $componentTreeProcessor;
	}

	/**
	 * Set a custom pattern-tree which specifies which patterns to load.
	 * 
	 * @param string $file
	 */
	public function setPatternsFile($file)
	{
		$this->customPatterns = $file;
	}

	/**
	 * Add Markdown text and get the parsed to HTML version back in the
	 * form of a \DomDocument. The \DomDocument has `<doc>` as the
	 * document element.
	 *  
	 * @see AnyMark\Parser.Parser::parse()
	 * @return \DomDocument
	 */
	public function parse($text)
	{
		# adding the \n for texts containing only a paragraph
		$text = $this->preProcess($text . "\n\n");

		$domDoc = $this->getParser()->parse($text);

		$this->postProcess($domDoc);

		return $domDoc;
	}

	private function getParser()
	{
		if ($this->parser)
		{
			return $this->parser;
		}

		$patternList = $this->fjor->get('AnyMark\\Pattern\\PatternList');
		$patternListFiller = new \AnyMark\Util\PatternListFiller($this->fjor);
		if ($this->customPatterns)
		{
			$file = $this->customPatterns;
		}
		else
		{
			$file = __DIR__ . DIRECTORY_SEPARATOR . 'Patterns.php';
		}
		$patternListFiller->fill($patternList, $file);
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

	private function postProcess(ElementTree $componentTree)
	{
		foreach ($this->postElementTreeProcessors as $processor)
		{
			$processor->process($componentTree);
		}
	}
}