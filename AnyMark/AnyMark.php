<?php

/**
 * @package AnyMark
 */
namespace AnyMark;

use Fjor\Fjor;
use AnyMark\Parser\Parser;
use AnyMark\Processor\TextProcessor;
use AnyMark\Processor\ElementTreeProcessor;
use ElementTree\ElementTree;

/**
 * @package AnyMark
 */
class AnyMark implements Parser
{
	private $preTextProcessors = array();

	private $postElementTreeProcessors = array();

	private $parser;

	/**
	 * Sets up the wiring of objects using Fjor.
	 * 
	 * @return \Fjor\Dsl\Dsl
	 */
	static public function defaultWiring($patternsFile = null)
	{
		$patternsFile = $patternsFile ?: __DIR__ . DIRECTORY_SEPARATOR . 'Patterns.php';

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
		$fjor->given('AnyMark\\Parser\\Parser')
			->thenUse('AnyMark\\Parser\\RecursiveReplacer');
		$fjor->given('AnyMark\\Pattern\\PatternConfig')
			->thenUse('AnyMark\\Pattern\\FileArrayPatternConfig');
		$fjor->given('AnyMark\\Pattern\\FileArrayPatternConfig')
			->constructWith(array($patternsFile));
		$fjor->given('AnyMark\\Pattern\\PatternFactory')
			->thenUse('AnyMark\\Pattern\\FjorPatternFactory');
		$fjor->given('AnyMark\\Pattern\\PatternTree')
			->thenUse('AnyMark\\Pattern\\PatternList');
		$fjor->setSingleton('AnyMark\\Processor\\Processors\\LinkDefinitionCollector');
		$fjor->setSingleton('AnyMark\\Pattern\\PatternList');

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

	public function __construct(Parser $parser)
	{
		$this->parser = $parser;
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

		$domDoc = $this->parser->parse($text);

		$this->postProcess($domDoc);

		return $domDoc;
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