<?php

namespace AnyMark;

use AnyMark\Api\Parser;
use AnyMark\Events\AfterParsing;
use AnyMark\Events\BeforeParsing;
use AnyMark\Events\PatternConfigFile;
use AnyMark\Events\PatternConfigLoaded;
use AnyMark\Pattern\FileArrayPatternConfig;
use AnyMark\Plugins\Detab\DetabRegistrar;
use AnyMark\Plugins\EmailObfuscator\EmailObfuscatorRegistrar;
use AnyMark\Plugins\EmptyLineFixer\EmptyLineFixerRegistrar;
use AnyMark\Plugins\EscapeRestorer\EscapeRestorerRegistrar;
use AnyMark\Plugins\HtmlEntities\HtmlEntitiesRegistrar;
use AnyMark\Plugins\NewLineStandardizer\NewLineStandardizerRegistrar;
use ElementTree\ElementTree;
use Epa\Api\ObserverStore;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;
use Epa\EventDispatcherFactory;
use Fjor\Api\ObjectGraphConstructor;
use Fjor\FjorFactory;

class AnyMark implements Parser
{
    use ObserverStore;

    private $parser;

    private $eventDispatcher;

    private $patternConfig;

    private $patternConfigFileEventThrown = false;

    /**
     * Sets up the wiring of objects and returns an instance.
     */
    public static function setup(ObjectGraphConstructor $fjor = null): Parser
    {
        # lots of recursion while adding patterns to tree
        ini_set('xdebug.max_nesting_level', 200);

        $patternsFile = __DIR__ . DIRECTORY_SEPARATOR . 'Patterns.php';

        if (!$fjor) {
            $fjor = FjorFactory::create();
        }

        $fjor->given('Fjor\\Api\\ObjectGraphConstructor')->thenUse($fjor);
        $fjor
            ->given('AnyMark\\Util\\InternalUrlBuilder')
            ->thenUse('AnyMark\\Util\\ExtensionlessUrlBuilder');
        $fjor->given('AnyMark\\Api\\Parser')
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
        $eventDispatcher = EventDispatcherFactory::create();
        $fjor->given('Epa\\Api\\Observable')
            ->andMethod('addObserver')
            ->addParam(array('Epa\\Api\\EventDispatcher'));
        $fjor->given('Epa\\Api\\EventDispatcher')
            ->thenUse($eventDispatcher);

        $anyMark = $fjor->get('\\AnyMark\\AnyMark');
        $anyMark->registerPlugin(new EmptyLineFixerRegistrar());
        $anyMark->registerPlugin(new NewLineStandardizerRegistrar());
        $anyMark->registerPlugin(new DetabRegistrar());
        $anyMark->registerPlugin(new HtmlEntitiesRegistrar());
        $anyMark->registerPlugin(new EscapeRestorerRegistrar());
        $anyMark->registerPlugin(
            $fjor->get('AnyMark\\Plugins\\LinkDefinitionCollector\\LinkDefinitionCollectorRegistrar')
        );
        $anyMark->registerPlugin(new EmailObfuscatorRegistrar());

        return $anyMark;
    }

    public function __construct(
        Parser $parser,
        EventDispatcher $eventDispatcher,
        FileArrayPatternConfig $patternConfig
    ) {
        $this->parser = $parser;
        $this->eventDispatcher = $eventDispatcher;
        $this->patternConfig = $patternConfig;
    }

    public function registerPlugin(Plugin $plugin): void
    {
        $this->eventDispatcher->addPlugin($plugin);
    }

    /**
     * Add Markdown text and get the 'parsed to HTML' version back in the
     * form of a `\ElementTree\ElementTree`.
     *
     * @see AnyMark\Parser.Parser::parse()
     */
    public function parse($text): ElementTree
    {
        if (!$this->patternConfigFileEventThrown) {
            $this->notify(new PatternConfigFile($this->patternConfig));
            $this->patternConfigFileEventThrown = true;
            $this->notify(new PatternConfigLoaded($this->patternConfig));
        }

        $beforeParsingEvent = new BeforeParsing($text . "\n\n");
        $this->notify($beforeParsingEvent);

        $tree = $this->parser->parse($beforeParsingEvent->getText());

        $afterParsingEvent = new AfterParsing($tree);
        $this->notify($afterParsingEvent);

        return $tree;
    }
}
