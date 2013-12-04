<?php

namespace AnyMark\EndToEndTests\Support;

class Tidy extends \PHPUnit_Framework_TestCase
{
	private $comparify;

	public function setup()
	{
		$this->comparify = new \Comparify\Comparify();
	}

	public function tidy($html)
	{
		return $this->comparify->transform($html);
	}
}