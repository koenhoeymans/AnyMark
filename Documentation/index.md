AnyMark Documentation
=====================

What is AnyMark?
----------------

AnyMark is a [Markdown](http://daringfireball.net/projects/markdown/syntax)
parser written in PHP.

Should I use AnyMark?
---------------------

AnyMark was written to be customizable. It was not written to be the
fastest Markdown parser available. PHPMarkdown is much faster and is
recommended if speed is your primary concern.

How can I use AnyMark?
----------------------

### Installation ###

AnyMark can be installed through [Composer](http://getcomposer.org/doc/00-intro.md).
The `composer.json` should contain at least the following:

	{
		"require": {
			"anymark/anymark": "0.2.*"
		}
	}

Composer installs the library in the `vendor` directory and you will need
to `require` the vendors autoload:

	require vendor/autoload.php;

Manual installation is also possible. AnyMark can be downloaded from
[github](https://github.com/koenhoeymans/fjor). It depends on [Fjor]
(https://github.com/koenhoeymans/fjor) which should be autoloaded too (see
the Fjor manual).


### Library Use ###

You can setup an AnyMark instance as follows:

	\AnyMark\AnyMark::setup();

This instantiates AnyMark with a default setup and all dependencies wired.

Parsing a Markdown document is then simple:

	$elementTree = $anyMark->parse('== AnyMark ==');

It will return a `\ElementTree\ElementTree` (see [https://github.com/koenhoeymans/ElementTree](https://github.com/koenhoeymans/ElementTree)).
If you want to save this to XML as a string call

	$result = $componentTree->toString();

It is called XmlStyle because it is not strictly XML. Eg there is not
necessarily a single root element (eg. a document can contain just
a set of paragraphs).

The AnyMark\ElementTree is a tree of components. These can be an `Element`, `Comment`,
`Text` or a `AnyMark\ElementTree` itself.

### Custom Patterns ###

A new pattern can be created by implementing `\AnyMark\Pattern\Pattern`. The best
way to see how this works is to look at some examples of AnyMark patterns.

Patterns can be added when the event `EditPatternConfigurationEvent` is thrown.
You can access this event by creating a plugin and let your plugin register a
callback for this event.

### Creating Plugins ###

AnyMark uses the [EPA library](https://github.com/koenhoeymans/epa) to create a
pluggable architecture. A plugin can be created by implementing `\Epa\Plugin`:

	class MyCustomPluginRegistrat implements \Epa\Plugin
	{
		public function register(EventMapper $mapper)
		{
			$mapper->registerForEvent(
				'AnyMark\\Events\\PatternConfigLoaded',
				function(PatternConfigLoaded $event) {
					$this->addPatterns($event->getPatternConfig());
				}
			);
		}

		public function addPatterns(Add $patternConfig)
		{
			$patternConfig
				->add('AnyMark\\EndToEndTests\\Support\\Patterns\\FooChange')
				->to('italic')
				->first(); 
		}
	}

The plugin registers a callback for an event (`registerForEvent`).


Syntax
------

The markup follows 'regular' [Markdown syntax](http://daringfireball.net/projects/markdown/syntax).