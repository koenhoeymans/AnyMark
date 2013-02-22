<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMark_Pattern_Patterns_DefinitionTermTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->dt = new \AnyMark\Pattern\Patterns\DefinitionTerm();
	}

	public function getPattern()
	{
		return $this->dt;
	}

	public function createDt($text)
	{
		$dt = $this->elementTree()->createElement('dt');
		$text = $this->elementTree()->createText($text);
		$dt->append($text);

		return $dt;
	}

	/**
	 * @test
	 */
	public function aTermCanBeFollowedByADefinition()
	{
		$text =
'para

term a
:	explanation

para';

		$this->assertEquals($this->createDt('term a'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function aTermCanBeFollowedByAnotherTermSharingTheSameDefinition()
	{
				$text =
'para

term a
term b
:	explanation

para';

		$this->assertEquals($this->createDt('term a'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function termsCanHaveMoreThanOneDefinition()
	{
				$text =
'para

term a
:	explanation x
:	explanation y

para';

		$this->assertEquals($this->createDt('term a'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function moreThanOneTermCanShareMoreThanOneDefinition()
	{
		$text =
'para

term a
term b
:	explanation
:	explanation

para';

		$this->assertEquals($this->createDt('term a'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function definitionListCanContainMoreThanOneTermWithoutNewlineBetweenPreviousDescriptionAndNewTerm()
	{
		$text =
'para

term a
:	explanation
term b
:	explanation

para';

		$this->assertEquals($this->createDt('term a'), $this->applyPattern($text));
	}
}