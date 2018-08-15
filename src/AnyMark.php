<?php

namespace AnyMark;

class AnyMark implements Api\Parser, Parser\Parser
{
    use \Epa\Api\ObserverStore;

    private $parser;

    private $eventDispatcher;

    private $patternConfig;

    private $patternConfigFileEventThrown = false;

    /**
     * Sets up the wiring of objects and returns an instance.
     */
    public static function setup(\Fjor\Api\ObjectGraphConstructor $fjor = null) : \AnyMark\Api\Parser
    {
        # lots of recursion while adding patterns to tree
        ini_set('xdebug.max_nesting_level', 200);

        $patternsFile = __DIR__.DIRECTORY_SEPARATOR.'Patterns.php';

        if (!$fjor) {
            $fjor = \Fjor\FjorFactory::create();
        }

        $fjor->given('Fjor\\Api\\ObjectGraphConstructor')->thenUse($fjor);
        $fjor
            ->given('AnyMark\\Util\\InternalUrlBuilder')
            ->thenUse('AnyMark\\Util\\ExtensionlessUrlBuilder');
        $fjor->given('AnyMark\\Parser\\Parser')
            ->thenUse('AnyMark\\Parser\\GlobalMatchRecursiveReplacer');
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
        $fjor->setSingleton('AnyMark\\Plugins\\LinkDefinitionCollector\\LinkDefinitionCollector');
        $fjor->setSingleton('AnyMark\\Pattern\\PatternList');
        $fjor->setSingleton('Epa\\Api\\EventDispatcher');
        $fjor->setSingleton('AnyMark\\Pattern\\FileArrayPatternConfig');
        $eventDispatcher = \Epa\EventDispatcherFactory::create();
        $fjor->given('Epa\\Api\\Observable')
            ->andMethod('addObserver')
            ->addParam(array('Epa\\Api\\EventDispatcher'));
        $fjor->given('Epa\\Api\\EventDispatcher')
            ->thenUse($eventDispatcher);

        $anyMark = $fjor->get('\\AnyMark\\AnyMark');
        $anyMark->registerPlugin(new \AnyMark\Plugins\EmptyLineFixer\EmptyLineFixerRegistrar());
        $anyMark->registerPlugin(new \AnyMark\Plugins\NewLineStandardizer\NewLineStandardizerRegistrar());
        $anyMark->registerPlugin(new \AnyMark\Plugins\Detab\DetabRegistrar());
        $anyMark->registerPlugin(new \AnyMark\Plugins\HtmlEntities\HtmlEntitiesRegistrar());
        $anyMark->registerPlugin(new \AnyMark\Plugins\EscapeRestorer\EscapeRestorerRegistrar());
        $anyMark->registerPlugin(
            $fjor->get('AnyMark\\Plugins\\LinkDefinitionCollector\\LinkDefinitionCollectorRegistrar')
        );
        $anyMark->registerPlugin(new \AnyMark\Plugins\EmailObfuscator\EmailObfuscatorRegistrar());

        return $anyMark;
    }

    public function __construct(
        Parser\Parser $parser,
        \Epa\Api\EventDispatcher $eventDispatcher,
        Pattern\FileArrayPatternConfig $patternConfig
    ) {
        $this->parser = $parser;
        $this->eventDispatcher = $eventDispatcher;
        $this->patternConfig = $patternConfig;
    }

    public function registerPlugin(\Epa\Api\Plugin $plugin) : void
    {
        $this->eventDispatcher->addPlugin($plugin);
    }

    /**
     * Add Markdown text and get the 'parsed to HTML' version back in the
     * form of a `\ElementTree\ElementTree`.
     *
     * @see AnyMark\Parser.Parser::parse()
     */
    public function parse($text) : \ElementTree\ElementTree
    {
        if (!$this->patternConfigFileEventThrown) {
            $this->notify(new \AnyMark\Events\PatternConfigFile($this->patternConfig));
            $this->patternConfigFileEventThrown = true;

            $this->notify(new \AnyMark\Events\PatternConfigLoaded($this->patternConfig));
        }

        $beforeParsingEvent = new \AnyMark\Events\BeforeParsing($text."\n\n");
        $this->notify($beforeParsingEvent);

        $tree = $this->parser->parse($beforeParsingEvent->getText());

        $afterParsingEvent = new \AnyMark\Events\AfterParsing($tree);
        $this->notify($afterParsingEvent);

        return $tree;
    }
}
