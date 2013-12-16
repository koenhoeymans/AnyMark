AnyMark Changelog
=================

*	0.10.0

	*	Fixed parser bug.
	*	Fixed manual HTML bug with attributes on new line.
	*	Fixed manual HTML bug containing code with tags.
	*	Fixed manual HTML bug handling white space before content.
	*	Fixed manual HTML bug not unindenting spaces.
	*	Manual HTML also uses list of block level tags now, not only rules to determine
		whether it is a match or not.
	*	Content in manual HTML is not unindented.
	*	Testing now compares output through [Comparify](https://github.com/koenhoeymans/Comparify)
	*	Removed unindentation for paragraphs.
	*	Ins and del elements can be both inline and block.
	*	Fixed soms bugs with entities and escaping.
	*	Fixed bugs with selfclosing elements.

*	0.9.6

	*	Headers are not allowed to be indented.

*	0.9.5

	*	Change to parsing algorithm: patterns are applied to text before subpatterns
		are applied (instead of directly applying subpatterns after a match).
	*	Hyperlink definitions are removed only at the end.

*	0.9.4

	*	Horizontal rule was not recognized when it had whitespace at the end.

*	0.9.3

	*	Fixed problems for external libraries by not correctly registering internally
		used plugins with public api events.

*	0.9.2

	*	Update to latest version of package Epa to resolve conflict with EventMapperMock.
	*	Documentation update.

*	0.9.1

	*	Using `htmlspecialchars` instead of `htmlentities` before output.

*	0.9.0

	*	Added original Markdown and PHPMarkdown tests.
	*	Indented code is followed by newline.
	*	Allowed <br> for manual HTML.
	*	Headers don't have a header id anymore. Conforms to standard Markdown.
	*	Removed italic pattern. `_` is now emphasis as it is with Markdown.
	*	Changed image attribute order.
	*	Links can have spaces in their urls.
	*	Manual block level HTML can contain only manual HTML.
	*	Ordered and unoredered lists can follow eachother without blank line in between.
	*	Hyperlink cannot be inside word boundaries.
	*	Emphasis bug fixes.
	*	Update to version 0.3.0 of package `ElementTree`.
	*	Quote style for attribute values in manual HTML is respected.
	*	Fixed `CustomPatterns` test.
	*	Only a defined list of characters can be escaped.
	*	Extracted escaping of characters to plugin.

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