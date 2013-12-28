AnyMark Documentation
=====================


What is AnyMark?
----------------

AnyMark is a [Markdown](http://daringfireball.net/projects/markdown/syntax)
parser written in PHP.


Why use AnyMark?
----------------

AnyMark was written to be customizable. It was not written to be the
fastest Markdown parser available. PHPMarkdown is much faster and is
recommended if speed is your primary concern.


Installation
------------

AnyMark can be installed through [Composer](http://getcomposer.org/doc/00-intro.md).
The `composer.json` should contain at least the following:

	{
		"require": {
			"anymark/anymark": "0.2.*"
		}
	}

Composer installs the library and all its dependencies in the `vendor` directory
and you will need to `require` the vendors autoload:

	require vendor/autoload.php;

With this you can start creating an instance of AnyMark (`\AnyMark\AnyMark::setup()`)
and using it (see below).

Manual installation is also possible. AnyMark can be downloaded from
[github](https://github.com/koenhoeymans/AnyMark). It has a couple of dependencies
which should be autoloaded for AnyMark to work:

	* [Fjor](https://github.com/koenhoeymans/fjor)
	* [ElementTree](https://github.com/koenhoeymans/ElementTree)
	* [Epa](https://github.com/koenhoeymans/epa)

You can check their documentation on how to install them.


Quick Use
---------

You can setup an AnyMark instance as follows:

	\AnyMark\AnyMark::setup();

This instantiates AnyMark with a default setup and all dependencies wired.

Parsing a Markdown document is then as simple as:

	$elementTree = $anyMark->parse('== AnyMark ==');

It will return a `\ElementTree\ElementTree` (see [https://github.com/koenhoeymans/ElementTree](https://github.com/koenhoeymans/ElementTree)).
If you want to save this as a string call

	$result = $componentTree->toString();

An `ElementTree` is not strictly XML. Eg there is not necessarily a single root element
(eg. a document can contain just a set of paragraphs).


Custom Patterns
---------------

A new pattern can be created by implementing `\AnyMark\Pattern\Pattern`. The best
way to see how this works is to look at some examples of AnyMark patterns.

The pattern itself can be added when the event `EditPatternConfigurationEvent` is thrown.
You can access this event by creating a plugin and let your plugin register a
callback for this event.


Modifying AnyMark
-----------------

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