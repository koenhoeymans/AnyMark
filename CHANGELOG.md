AnyMark Changelog
=================

*	0.8.0

	*	Parser implementation changed.
	*	Changes to default pattern order.
	*	PatternList bug fixes.
	*	Blockquote bug fixes.
	*	Header bug fixes.
	*	Hyperlink bug fixes. 
	*	List items that are paragraphs get blank line within the item.
	*	Fixed link pattern bugs with nested brackets.
	*	Seperated manual html into block and inline pattern.
	*	Undid some changes to the tests from PHPMarkdown.
	*	Improved strong, emphasis and italic patterns.
	*	EmailObfuscator refactored, renamed test.

*	0.7.1

	*	Added `Epa\MetaEventNamePlugin` by default.

*	0.7.0

	*	Extracted public api.

*	0.6.1

	*	Moved `PatternConfigLoaded` event to class `AnyMark`. It would not be
		thrown after a new patternfile was loaded.

*	0.6.0

	*	Removed 'Extra' patterns.
	*	Added plugin capabilities to replace processors.

*	0.5.0

	*	Instantiating `AnyMark` is simpler and more meaningful.

			\AnyMark\AnyMark::createWith(\AnyMark\AnyMark::defaultSetup());

*	0.4.0

	*	Markdown lists are now one pattern instead of two (one for the list and
		one for the list items).
	*	Pattern relationships and processing order are no longer contained in an
		`ini` file but through an array.

*	0.3.2

	*	Added double newline (`\n\n`) before the text string to parse. Otherwise
		a single paragraph wouldn't be recognised as such.
	*	Change InternalUrlBuilder interface definition.

*	0.3.1

	*	More flexible elementtree version requirement.

*	0.3.0

	*	Extracted AnyMark\ElementTree into a seperate library which
		is now used (ElementTree\ElementTree).

*	0.2.0

	*	Now relies on a AnyMark\ElementTree instead of the Dom.

*	0.1.3

	*	Fenced code blocks accept attributes and an indication of the code language.

*	0.1.2

	*	Manual HTML was not allowed for some inline patterns. `Patterns.ini` updated.

*	0.1.1

	*	Allowing newlines in inline link syntax.

*	0.1.0

	*	Initial release.