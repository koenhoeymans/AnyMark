<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_TableOfContentsTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->headerFinder =
			$this->getMockBuilder('\\AnyMark\\Pattern\\Patterns\\TableOfContents\\HeaderFinder')
					->disableOriginalConstructor()
					->getMock();
		$this->docFileRetriever =
			$this->getMockBuilder('\\AnyMark\\Util\\DocFileRetriever')
					->disableOriginalConstructor()
					->getMock();
		$this->internalUrlBuilder =
			$this->getMockBuilder('\\AnyMark\\Util\\InternalUrlBuilder')
					->disableOriginalConstructor()
					->getMock();
		$this->toc = new \AnyMark\Pattern\Patterns\TableOfContents(
			$this->headerFinder,
			$this->docFileRetriever,
			$this->internalUrlBuilder
		);
	}

	public function getPattern()
	{
		return $this->toc;
	}

	/**
	 * @test
	 */
	public function createsLocalTocInDomFromMatch()
	{
		$text = "{table of contents}

header
----

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'header'))
			));

		$doc = new \DOMDocument();
		$ul = $doc->createElement('ul');
		$doc->appendChild($ul);
		$li = $doc->createElement('li');
		$ul->appendChild($li);
		$a = $doc->createElement('a', 'header');
		$li->appendChild($a);
		$a->setAttribute('href', '#header');

		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function respectsLevelOfHeadersThroughSublists()
	{
		$text = "{table of contents}

header
----

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'header'),
				array('title' => 'subheader', 'level' => 2, 'id' => 'subheader')
			)));

		$doc = new \DOMDocument();
		$ul = $doc->createElement('ul');
		$doc->appendChild($ul);
		$li = $doc->createElement('li');
		$ul->appendChild($li);
		$a = $doc->createElement('a', 'header');
		$li->appendChild($a);
		$a->setAttribute('href', '#header');

		$subUl = $doc->createElement('ul');
		$li->appendChild($subUl);
		$subLi = $doc->createElement('li');
		$subUl->appendChild($subLi);
		$subA = $doc->createElement('a', 'subheader');
		$subLi->appendChild($subA);
		$subA->setAttribute('href', '#subheader');

		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function createsNestedToc()
	{
		$text = "{table of contents}

header
----

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header1a', 'level' => 1, 'id' => 'header1a'),
				array('title' => 'header2a', 'level' => 2, 'id' => 'header2a'),
				array('title' => 'header2b', 'level' => 2, 'id' => 'header2b'),
				array('title' => 'header1b', 'level' => 1, 'id' => 'header1b')
			)));

		$doc = new \DOMDocument();
		$ul1 = $doc->createElement('ul');
		$doc->appendChild($ul1);

		$li1 = $doc->createElement('li');
		$ul1->appendChild($li1);
		$a1 = $doc->createElement('a', 'header1a');
		$li1->appendChild($a1);
		$a1->setAttribute('href', '#header1a');

		$li2 = $doc->createElement('li');
		$ul1->appendChild($li2);
		$a2 = $doc->createElement('a', 'header1b');
		$li2->appendChild($a2);
		$a2->setAttribute('href', '#header1b');

		$subUl1 = $doc->createElement('ul');
		$li1->appendChild($subUl1);

		$subLi1 = $doc->createElement('li');
		$subUl1->appendChild($subLi1);
		$subA1 = $doc->createElement('a', 'header2a');
		$subLi1->appendChild($subA1);
		$subA1->setAttribute('href', '#header2a');

		$subLi2 = $doc->createElement('li');
		$subUl1->appendChild($subLi2);
		$subA2 = $doc->createElement('a', 'header2b');
		$subLi2->appendChild($subA2);
		$subA2->setAttribute('href', '#header2b');

		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function tocIsLimitedToItsSectionDeterminedByHeaderLevel()
	{
		$text = 
"header
---

paragraph

{table of contents}

subheader
===

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'subheader', 'level' => 2, 'id' => 'subheader')
			)));

		$doc = new \DOMDocument();
		$ul1 = $doc->createElement('ul');
		$doc->appendChild($ul1);
		$li1 = $doc->createElement('li');
		$ul1->appendChild($li1);
		$a1 = $doc->createElement('a', 'subheader');
		$li1->appendChild($a1);
		$a1->setAttribute('href', '#subheader');

		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function tocOfSectionStopsAtSectionWithHigherLevelHeader()
	{
		$text = 
"header
---

paragraph

{table of contents}

subheader
===

paragraph

other header
---

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'subheader', 'level' => 2, 'id' => 'subheader'),
				array('title' => 'other header', 'level' => 1, 'id' => 'other-header')
			)));

				$doc = new \DOMDocument();
		$ul1 = $doc->createElement('ul');
		$doc->appendChild($ul1);

		$li1 = $doc->createElement('li');
		$ul1->appendChild($li1);
		$a1 = $doc->createElement('a', 'subheader');
		$li1->appendChild($a1);
		$a1->setAttribute('href', '#subheader');

		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function depthOptionLimitsDepthOfToc()
	{
		$text = 
"{table of contents}
	depth: 1

header
---

paragraph

subheader
===

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'header'),
				array('title' => 'subheader', 'level' => 2, 'id' => 'subheader')
			)));

		$doc = new \DOMDocument();
		$ul1 = $doc->createElement('ul');
		$doc->appendChild($ul1);

		$li1 = $doc->createElement('li');
		$ul1->appendChild($li1);
		$a1 = $doc->createElement('a', 'header');
		$li1->appendChild($a1);
		$a1->setAttribute('href', '#header');

		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function addingFileNameIncludesHeadersFromThatFileAfterCurrentDocumentHeaders()
	{
		$text =
"{table of contents}

	Includedfile

header
----

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'header')
			)));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'included header', 'level' => 1, 'id' => 'included-header')
			)));
		$this->docFileRetriever
			->expects($this->any())
			->method('retrieve')
			->with('Includedfile')
			->will($this->returnValue(
"included header
----

some text"
			));

		$this->internalUrlBuilder
			->expects($this->atLeastOnce())
			->method('createRelativeLink')
			->will($this->returnValue('Includedfile.html'));

		$doc = new \DOMDocument();
		$ul1 = $doc->createElement('ul');
		$doc->appendChild($ul1);

		$li1 = $doc->createElement('li');
		$ul1->appendChild($li1);
		$a1 = $doc->createElement('a', 'header');
		$li1->appendChild($a1);
		$a1->setAttribute('href', '#header');

		$li2 = $doc->createElement('li');
		$ul1->appendChild($li2);
		$a2 = $doc->createElement('a', 'included header');
		$li2->appendChild($a2);
		$a2->setAttribute('href', 'Includedfile.html#included-header');

		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function firstEncounteredHeaderInCurrentDocumentDeterminesHighestLevel()
	{
		$text =
"level1
===

{table of contents}

	Includedfile

level2
---

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level2', 'level' => 2, 'id' => 'level2')
			)));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level3', 'level' => 3, 'id' => 'level3')
			)));
		$this->docFileRetriever
			->expects($this->any())
			->method('retrieve')
			->with('Includedfile')
			->will($this->returnValue(
"level3
+++

some text"
			));

		$this->internalUrlBuilder
			->expects($this->atLeastOnce())
			->method('createRelativeLink')
			->will($this->returnValue('Includedfile.html'));

		$doc = new \DOMDocument();
		$ul1 = $doc->createElement('ul');
		$doc->appendChild($ul1);

		$li1 = $doc->createElement('li');
		$ul1->appendChild($li1);
		$a1 = $doc->createElement('a', 'level2');
		$li1->appendChild($a1);
		$a1->setAttribute('href', '#level2');

		$subUl1 = $doc->createElement('ul');
		$li1->appendChild($subUl1);

		$subLi1 = $doc->createElement('li');
		$subUl1->appendChild($subLi1);
		$subA1 = $doc->createElement('a', 'level3');
		$subLi1->appendChild($subA1);
		$subA1->setAttribute('href', 'Includedfile.html#level3');

		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function moreThanOneFileCanBeSpecified()
	{
		$text =
"{table of contents}

	Includedfile1
	Includedfile2

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array()));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level1a', 'level' => 1, 'id' => 'level1a')
			)));
		$this->headerFinder
			->expects($this->at(2))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'level1b', 'level' => 1, 'id' => 'level1b')
			)));
		$this->docFileRetriever
			->expects($this->at(0))
			->method('retrieve')
			->with('Includedfile1')
			->will($this->returnValue(
"level1a
+++

some text"
			));
		$this->docFileRetriever
			->expects($this->at(1))
			->method('retrieve')
			->with('Includedfile2')
			->will($this->returnValue(
"level1b
+++

some text"
			));

		$this->internalUrlBuilder
			->expects($this->at(0))
			->method('createRelativeLink')
			->will($this->returnValue('Includedfile1.html'));
		$this->internalUrlBuilder
			->expects($this->at(1))
			->method('createRelativeLink')
			->will($this->returnValue('Includedfile2.html'));

		$doc = new \DOMDocument();
		$ul1 = $doc->createElement('ul');
		$doc->appendChild($ul1);

		$li1 = $doc->createElement('li');
		$ul1->appendChild($li1);
		$a1 = $doc->createElement('a', 'level1a');
		$li1->appendChild($a1);
		$a1->setAttribute('href', 'Includedfile1.html#level1a');

		$li1 = $doc->createElement('li');
		$ul1->appendChild($li1);
		$a1 = $doc->createElement('a', 'level1b');
		$li1->appendChild($a1);
		$a1->setAttribute('href', 'Includedfile2.html#level1b');

		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function usesTocOfIncludedFiles()
	{
		$text =
"{table of contents}

	Includedfile

paragraph";

		$this->headerFinder
			->expects($this->at(0))
			->method('getHeadersSequentially')
			->will($this->returnValue(array()));
		$this->headerFinder
			->expects($this->at(1))
			->method('getHeadersSequentially')
			->will($this->returnValue(array()));
		$this->headerFinder
			->expects($this->at(2))
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'header')
			)));
		$this->docFileRetriever
			->expects($this->at(0))
			->method('retrieve')
			->with('Includedfile')
			->will($this->returnValue(
"{table of contents}

	Subincludedfile

paragraph"
			));
		$this->docFileRetriever
			->expects($this->at(1))
			->method('retrieve')
			->with('Subincludedfile')
			->will($this->returnValue(
"header
---

some text"
			));

		$this->internalUrlBuilder
			->expects($this->atLeastOnce())
			->method('createRelativeLink')
			->will($this->returnValue('Subincludedfile.html'));

		$doc = new \DOMDocument();
		$ul1 = $doc->createElement('ul');
		$doc->appendChild($ul1);

		$li1 = $doc->createElement('li');
		$ul1->appendChild($li1);
		$a1 = $doc->createElement('a', 'header');
		$li1->appendChild($a1);
		$a1->setAttribute('href', 'Subincludedfile.html#header');

		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function addsLinkToHeaderUsingHeaderId()
	{
		$text = "{table of contents}

header
----

paragraph";

		$this->headerFinder
			->expects($this->any())
			->method('getHeadersSequentially')
			->will($this->returnValue(array(
				array('title' => 'header', 'level' => 1, 'id' => 'xyz'))
			));

		$doc = new \DOMDocument();
		$ul = $doc->createElement('ul');
		$doc->appendChild($ul);
		$li = $doc->createElement('li');
		$ul->appendChild($li);
		$a = $doc->createElement('a', 'header');
		$li->appendChild($a);
		$a->setAttribute('href', '#xyz');

		$this->assertCreatesDomFromText($doc, $text);
	}

	/**
	 * @test
	 */
	public function buildsTocFromDomDocument()
	{
		$domDoc1 = new \DOMDocument();
		$dom1h1 = $domDoc1->createElement('h1', 'header');
		$domDoc1->appendChild($dom1h1);
		$dom1h1->setAttribute('id', 'header');

		$domDoc2 = new \DOMDocument();
		$dom2h1 = $domDoc2->createElement('h1', 'header');
		$domDoc2->appendChild($dom2h1);
		$dom2h1->setAttribute('id', 'header');

		$this->assertEquals(
			$this->toc->createTocNode($domDoc1),
			$dom2h1
		);
	}

	/**
	 * @test
	 */
	public function aCustomPageTitleCanBeSpecified()
	{
		$text = "{table of contents}

	new title <page>

paragraph";

		$this->toc->getSubpages($text);

		$this->assertEquals('new title', $this->toc->getSpecifiedTitleForPage('page'));
	}
}