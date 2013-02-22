<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_NoteTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->note = new \AnyMark\Pattern\Patterns\Note();
	}

	public function getPattern()
	{
		return $this->note;
	}

	/**
	 * @test
	 */
	public function noteIsCreatedByUsingNoteAndTheTextOnNextLineIndented()
	{
		$text =
"This is a paragraph.

!Note
	This is a note.

Another paragraph.";

		$div = $this->elementTree()->createElement('div');
		$div->append(new \ElementTree\ElementTreeText('This is a note.'));
		$div->setAttribute('class', 'note');

		$this->assertEquals($div, $this->applyPattern($text));
	}
}