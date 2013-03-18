AnyMark Changelog
=================

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