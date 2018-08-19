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

	$anyMark = \AnyMark\AnyMark::setup();

This instantiates AnyMark with a default setup and all dependencies wired.

Parsing a Markdown document is then as simple as:

	$elementTree = $anyMark->parse('== AnyMark ==');

It will return a `\ElementTree\ElementTree` (see [https://github.com/koenhoeymans/ElementTree](https://github.com/koenhoeymans/ElementTree)).
If you want to save this as a string call

	$result = $componentTree->toString();

An `ElementTree` is not strictly XML. Eg there is not necessarily a single root element
(eg. a document can contain just a set of paragraphs).


Modifying AnyMark
-----------------

AnyMark uses the [EPA library](https://github.com/koenhoeymans/epa) to create a
pluggable architecture. It allows you to create plugins. Plugins registers a callback
for an event.

We'll create an example plugin for AnyMark that modifies the text to be parsed and
adds Markdown emphasis to all occurences of 'AnyMark'. If we parse the text

	This text will be parsed by the AnyMark library.

our plugin should change it to

	This text will be parsed by the *AnyMark* library.

before parsing.

One of the events that are thrown by AnyMark is `BeforeParsingEvent`. If you look
in the `PublicApi` folder you'll see that this is actually an interface and explains
what the callback, which will be passed the actual event that implements this interface,
can expect from it. We find two methods `getText` and `setText` that seem to be
exactly what we need. Our task is thus to create a plugin and let it register a
callback for this `BeforeParsingEvent` so that it can manipulate the text.

A plugin can be created by implementing `\Epa\Plugin`:

	class OurCustomPlugin implements \Epa\Plugin
	{
		public function register(EventMapper $mapper)
		{
			// register our callback
		}
	}

As we see there's only one method that we need to implement:
`register(EventMapper $mapper)`. The `EventMapper` is for registering our callback
to an event with `registerForEvent` taking two arguments: the name of the event (our
interface in the `PublicApi` folder specifies the name is `BeforeParsingEvent`) and
the callback.

	class OurCustomPlugin implements \Epa\Plugin
	{
		public function register(EventMapper $mapper)
		{
			$mapper->registerForEvent(
				'BeforeParsingEvent',
				function(\AnyMark\PublicApi\BeforeParsingEvent $event) {
					// do our work with the event
				}
			);
		}
	}

Now we can use the event and change the text:

	class OurCustomPlugin implements \Epa\Plugin
	{
		public function register(\Epa\EventMapper $mapper)
		{
			$mapper->registerForEvent(
				'BeforeParsingEvent',
				function(\AnyMark\PublicApi\BeforeParsingEvent $event) {
					$this->emphasizeAnyMark($event);
				}
			);
		}

		private function emphasizeAnyMark(\AnyMark\PublicApi\BeforeParsingEvent $event)
		{
			$text = $event->getText();
			return str_replace('AnyMark', '*AnyMark*', $text);
			$event->setText($text);
		}
	}

After we created the plugin there's one final step and that is telling AnyMark
we want to use it:

	$anyMark->registerPlugin(new OurCustomPlugin());


Custom Patterns
---------------

A new pattern can be created by implementing `\AnyMark\Pattern\Pattern`. The best
way to see how this works is to look at some examples of AnyMark patterns.

The pattern itself can be added when the event `PatternConfigLoaded` is thrown.
You can access this event by creating a plugin and let your plugin register a
callback for this event.