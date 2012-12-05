<?php

namespace AnyMark\EndToEndTests\Support;

class Tidy extends \PHPUnit_Framework_TestCase
{
	public function tidy($html)
	{
		// @todo normalize if tidy not available
		// @todo set config so that nothings changed but styling
		if (function_exists('tidy_parse_string'))
		{
			$config = array(
	           'indent'         => true,
	           'output-xhtml'   => true,
	           'wrap'           => 200,
	           'tab-size'		=> 4,
	           'preserve-entities'	=> 1,
	           'quote-ampersand'	=> 0,
	           'quote-marks'	=> 0,
	           'char-encoding'	=> 'utf8',
	           'clean' => false
			);

			$tidy = tidy_parse_string($html, $config);
			$html = tidy_get_output($tidy);
		}

		return $html;
	}
}