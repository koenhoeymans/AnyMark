<?php

/**
 * @package AnyMark
 */
namespace AnyMark;

use AnyMark\Pattern\PatternConfigDsl\Add;
use AnyMark\Parser\Parser;
use AnyMark\Processor\TextProcessor;
use AnyMark\Processor\ElementTreeProcessor;
use ElementTree\ElementTree;
use Epa\EventDispatcher;
use Epa\Plugin;
use Epa\Pluggable;
use Epa\Observable;
use Fjor\Fjor;


/**
 * @package AnyMark
 */
class AnyMark implements Parser, Observable
{
	use Pluggable;

	private $parser;

	private $eventDispatcher;

	private $patternConfig;

	private $patternConfigFileEventThrown = false;

	/**
	 * Sets up the wiring of objects and return an instance.
	 * 
	 * @return \AnyMark\AnyMark
	 */
	static public function setup()
	{
		$patternsFile = __DIR__ . DIRECTORY_SEPARATOR . 'Patterns.php';

		$fjor = \Fjor\Fjor::defaultSetup();

		$fjor->given('Fjor\\Fjor')->thenUse($fjor);
		$fjor
			->given('AnyMark\\Util\\InternalUrlBuilder')
			->thenUse('AnyMark\\Util\\ExtensionlessUrlBuilder');
		$fjor->given('AnyMark\\Parser\\Parser')
			->thenUse('AnyMark\\Parser\\RecursiveReplacer');
		$fjor->given('AnyMark\\Pattern\\PatternConfig')
			->thenUse('AnyMark\\Pattern\\FileArrayPatternConfig');
		$fjor->given('AnyMark\\Pattern\\PatternConfigDsl\\Add')
			->thenUse('AnyMark\\Pattern\\FileArrayPatternConfig');
		$fjor->given('AnyMark\\Pattern\\FileArrayPatternConfig')
			->andMethod('fillFrom')
			->addParam(array($patternsFile));
		$fjor->given('AnyMark\\Pattern\\PatternFactory')
			->thenUse('AnyMark\\Pattern\\FjorPatternFactory');
		$fjor->given('AnyMark\\Pattern\\PatternTree')
			->thenUse('AnyMark\\Pattern\\PatternList');
		$fjor->setSingleton('AnyMark\\Plugins\\LinkDefinitionCollector');
		$fjor->setSingleton('AnyMark\\Pattern\\PatternList');
		$fjor->setSingleton('Epa\\EventDispatcher');
		$fjor->setSingleton('AnyMark\\Pattern\\FileArrayPatternConfig');
		$fjor->given('Epa\\Observable')
			->andMethod('addObserver')
			->addParam(array('Epa\\EventDispatcher'));

		$anyMark = $fjor->get('\\AnyMark\\AnyMark');

		$anyMark->registerPlugin(new \AnyMark\Plugins\EmptyLineFixer());
		$anyMark->registerPlugin(new \AnyMark\Plugins\NewLineStandardizer());
		$anyMark->registerPlugin(new \AnyMark\Plugins\Detab());
		$anyMark->registerPlugin($fjor->get('AnyMark\\Plugins\\LinkDefinitionCollector'));
		$anyMark->registerPlugin(new \AnyMark\Plugins\EmailObfuscator());

		return $anyMark;
	}

	public function __construct(
		Parser $parser, EventDispatcher $eventDispatcher, Add $patternConfig
	) {
		$this->parser = $parser;
		$this->eventDispatcher = $eventDispatcher;
		$this->patternConfig = $patternConfig;
	}

	public function registerPlugin(Plugin $plugin)
	{
		$this->eventDispatcher->registerPlugin($plugin);
	}

	/**
	 * Add Markdown text and get the parsed to HTML version back in the
	 * form of a `\ElementTree\ElementTree`.
	 *  
	 * @see AnyMark\Parser.Parser::parse()
	 * @return \ElementTree\ElementTree
	 */
	public function parse($text)
	{
		if (!$this->patternConfigFileEventThrown)
		{
			$this->notify(new \AnyMark\Events\PatternConfigFile($this->patternConfig));
			$this->patternConfigFileEventThrown = true;

			$this->notify(new \AnyMark\Events\PatternConfigLoaded($this->patternConfig));
		}

		$beforeParsingEvent = new \AnyMark\Events\BeforeParsing($text . "\n\n");
		$this->notify($beforeParsingEvent);

		$tree = $this->parser->parse($beforeParsingEvent->getText());

		$afterParsingEvent = new \AnyMark\Events\AfterParsing($tree);
		$this->notify($afterParsingEvent);

		return $tree;
	}
}