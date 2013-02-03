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
			"anymark/anymark": "0.1.*"
		}
	}

Manual installation is also possible. AnyMark can be downloaded from
[github](https://github.com/koenhoeymans/fjor). It depends on [Fjor]
(https://github.com/koenhoeymans/fjor) which should be autoloaded too (see
the Fjor manual).


### Library Use ###

Instatiating AnyMark is a two step procedure.

	$fjor = \AnyMark\AnyMark::setup();

This creates an instance of Fjor and wires the dependencies. You can change
these dependencies. Next create an instance of AnyMark:

	$anyMark = $fjor->get('AnyMark\\AnyMark');

Parsing a Markdown document is simple:

	$domDocument = $anyMark->parse('== AnyMark ==');

It will return a `\DomDocument`. If you want to save this to XML as a string call

	$xmlString = $anyMark->saveXml($domDocument);

You could call `\DomDocument::saveXml()` but this could lead to unwanted results.
The reason is that loading the text into the Dom translated encoded characters
like `&` (this becoming `&amp;`) and this is done also while
calling `\DomDocument::saveXml()`, being one time too many.

### Custom Patterns ###

What patterns, the order in which the patterns are handled and its relations
are defined in an ini file. The default ini file can be found in the AnyMark
directory. AnyMark accepts a custom ini file this way:

	$anyMark->setPatternsIni($myCustomIniFile);


Syntax
------

The markup follows mostly regular Markdown markup with
but with some differences.


### Handling of special characters ###

As in the [Markdown](http://daringfireball.net/projects/markdown/syntax) implementation
the ampersand (&), greater than sign (>) and lesser than sign (>) are automatically converted to
entities so you don't have to worry about that when you are writing. The ampersand is not replaced
when it is part of an entity (except in code blocks).

When you like to avoid that a character triggers a special meaning you can escape it with a backslash.

	This \*word\* won't be rendered as <em>word</em> but as *word*.


### Raw HTML ###

Not everything is possible with the simple syntax rules AnyMark uses. If you would need to
you can add raw HTML to the text.

A paragraph needs a blank line before and after. That means that you can add HTML like this

	A short paragraph.

	<div>

	A paragraph withing manually added tags.

	</div>

	Note the blank lines.

The paragraph will be placed within the div element.

If the pattern is indented, as when you are writing a paragraph in a list item, the tags that
surround it should also be indented.

	* item a
	* Item b has an extra paragraph.
	
	  <div>

	  This paragraph is within div tags that need to be indented.

	  </div>


### Headers ###

[Atx](http://www.aaronsw.com/2002/atx/intro) style headers are headers preceded
by one or more dash signs. The number of them determines its level.

	## level 2 header

[Setext](http://docutils.sourceforge.net/mirror/setext.html) style headers are
supported too but are made a bit more powerful. They are assigned a level in order
of appearance. The first used markup will be used as a level 1 header throughout
the document.

Allowed signs to mark headers are: -=+*^#. At least three such signs must be used and 
they can appear before or before and after the header text.

Some examples will make this clear:

	---
	a header
	---

	=_=_=_=
	another header
	*******

The first three signs are what counts. Eg the following two will have the same level:

	---***
	a header
	---***

	---+++
	same level header
	---+++

Different than with regular Markdown output is that AnyMark will generate id's based
upone the header text. It will translate to something like this:

	<h1 id="a-header">a header</a>

First all white spaces are converted to `-`. Then all characters that are not
alfanumeric, underscore, dash or point are removed. After this everything up
to the first letter is removed. All inspired by Pandoc.

When several headers have the same id a number suffix is added to distinguish them.

### Paragraphs ###

A paragraph is formed by text preceded and followed by a blank line.

	This is a paragraph. It is followed by a blank line.
	
	This is also a paragraph. It is preceded by a blank line. It is not followed by a 
	blank line because it is the end of the virtual file.

Indentation can be used to make the document more readable.

In Markdown a paragraph can be immediately followed by a header or blockquote:

	This is a paragraph.
	# header

In my implementation this is not possible. A paragraph is only ended with a blank line
or indented text:

	This is a paragraph.
	  # header indented

One reason is that this makes it less easy to create unexpected headers:

	My favorite track on the cd is track
	#8.


### Code ###

The standard option is to write code by indenting text four spaces or a tab:

	A paragraph.

		This is code.

	The next paragraph.

Code is preceded by the word 'CODE:' with the actual code following on the second 
line. Both are indented. The code block must be indented in respect to the previous
lines.

	This is a regular paragraph with samples of code blocks:

		CODE:
		Note the 'CODE:' word. It is followed by the actual code.

		CODE:

			White space is possible.

		CODE: This is another possibility.

	This is the next paragraph.

The code block must be preceded and followed by a blank line. Note that the 'code' word is case
insensitive.

Another option is to write code between two lines of at least three tildes.

	~~~
	this is also treated as code
	~~~

This type of code doesn't need to be indented. When you need to write three tildes as code
you'll have to indent it though:

	A short paragraph.

	~~~~~~~~~~
		~~~
		meta code example
		~~~
	~~~~~~~~~~

	Another Short paragraph.

This way allows you to specify custom attributes which can be interesting for code
highlighting:

	~~~ {#myId .myClass foo="bar"}
	$this->createCodeExample();
	~~~

The `#` creates an id, the `.` creates a class, `foo="bar"` adds an attribute `foo`
with a value of `bar`. There's a shortcut to specifying the language of the code
fragment:

	~~~ PHP
	$this->createCodeExample();
	~~~

The `PHP` notifies the parser that this is a code block
containing PHP code so it will add a class `language-php` to the code element.

Inline code can be written using backticks:

	You could write `$this->foo` for example.


### Note ###

Notes can be created as follows:

	CODE:
		!NOTE This is a note.

			This sentence is still
		part of the note.

		This sentence is outside the note.

The word 'note' is case insensitive. A note block is ended with a blank line
followed by text equally or less indented.


### blockquote ###

Blockquote are quoted text preceded by `>`.

	> this is a blockquote
	> continues on the next line

Lazy quoting is also possible.

	> this is a blockquote
	continues on the next line

Blockquotes can contain other markup.

	> this is a blockquote
	>
	> > within a blockquote
	> > another blockquote
	>
	> notice the blank lines


### Lists ###

For unordered lists you can use *, + or -.

	+ an item
	+ other item

For ordered lists use a number followed by a dot.

	1. First item
	2. Second item

The actual numbers used do not influence order. Instead of numbers you can also
use the hash sign followed by a dot (inspired by [Pandoc](http://johnmacfarlane.net/pandoc/README.html#ordered-lists).

	#. First item
	#. Second item

A list needs a blank line before and after it if the list item starts unindented. If you
use one to three spaces a blank line is not necessary.

	A paragraph.
	 * a list item indented one space

Nested lists are possible if you follow the rule of either a blank
line or space indentation.

	* item
	* item with a sublist

	    * item in sublist
	    * other item in sublist

	* item in main list

This is a difference with Markdown which requires only a blank line in the first
level of the list.

### Horizontal rules ###

Three or more hyphens, underscores or asterisks on its own line. Spacing is allowed
but take not that if you start the line with four or more spaces it will become code.

	* * *
	---
	 _ __

All three will produce a horizontal rule.


### Links ###

An url between `<` and `>` is automatically converted to a hyperlink. Thus

	<http://www.mysite.com>

becomes

	<a href="http://www.mysite.com">http://www.mysite.com</a>

Full links contain anchore text, the url and optional title text:

	(anchor text)[url "title text"]

Different than with pure Markdown syntax the anchore text and url can be
placed on different lines.

	This is also possible for a link: (anchor text)
	[url "title text"].

A link reference can be used to place the actual url somewhere else in your text. Note that
single brackets are used. The link definition can contain the title attribute:

	This is a [link] [1] in text.

	The link definition can be placed somewhere else on its own line, 
	optionally indented one to three spaces.

	[1]: http://example.com "title"

 [1]: http://daringfireball.net/projects/markdown/syntax#link


### Images ###

The syntax for images follows the [Markdownimplementation](http://daringfireball.net/projects/markdown/syntax#link).
The two styles are inline and reference.

Inline images are written as follows:

	![alternate text](link/to/image "optional title")

The title can be between single and double quotes.

Reference style images have the following syntax:

	![alternate text][link id]

The link id syntax is the same as with regular links. You can place the link
definition anywhere in the text.

	[link id]: link/to/image "optional title"


### Email ###

An automatic email link is created by placing the email address between a lesser than
and greater than sign.

	Mail me at my <mail@example.com>.

The regular link syntax allows to create an email link with anchor text.

	This is [my email](mailto:mail@example.com).

### Italic, emphasized and important text ###

Words can be italicized by wrapping them between underscores. Underscores 
in words are left as is. Underscores that span more than one word are allowed.

	A couple of _italicized words_.

Becomes

	A couple of <i>italicized words</i>.

Emphasized text is placed between asterisks:

	Emphasized text is placed *between* asterisks.

Becomes

	Emphasized text is placed <em>between</em> asterisks.

Important text is achieved by putting words between double asterisks:

	A sentence with **important** text.

Becomes

	A sentence with <strong>important</strong> text.

!Note: Importance is chosen instead of strong importance to reflect the change coming with HTML5. See
the [section on changed elements](http://www.w3.org/TR/html5-diff/#changed-elements).

There is a difference between this implementation and that of Markdown. Italicized text doesn't
exist in Markdown. The syntax I use for emphasized text in Markdown (`_underscore_`) produces
italicized text.


### Newlines ###

Newlines can be inserted by adding a double space at the end of the line. This
follows Markdown convention. Writing documentation is a different context from
writing forum or blog posts. I think there are less cases in documentation where
having a hard line break would be beneficial. More technically oriented users
will also have less problem adoption this, although admittedly it is
counterintuitive.

I've considered adopting the [suggestion](http://michelf.com/weblog/2009/markdown-sustainability/)
in the comments of allowing hard line breaks in top-level paragraphs. But
it adds complexity in the rules.

Readability should be the prime goal and I believe that for writing documentation
using two spaces has the most benefits in that regard.


### Definition lists ###

A definition list is a list of terms and definitions of these terms. The term is
put on its own line, while the definition starts on the next line and is preceded
by a colon. This could be a definition list:

	term a
	:	definition of term a

 Multiple terms can share the same definition and a term can have multiple
 descriptions, while the definition can contain other markup elements.

	term a
	term b
	:	Explanation of term a and b
		that continues on the next line.

		It contains more than one paragraph.

	:	An alternative explanation is possible.