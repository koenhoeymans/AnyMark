<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins;

use AnyMark\PublicApi\BeforeParsingEvent;
use Epa\EventMapper;
use Epa\Plugin;

/**
 * @package AnyMark
 */
class Detab implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'BeforeParsingEvent', function(BeforeParsingEvent $event) {
				$event->setText($this->replace($event->getText()));
			}
		);
	}

	private function replace($text)
	{
		# adapted from PHP Markdown
		return preg_replace_callback(
			"/^.*?(?<space_before>[ ]?)\t.*$/m",
			function ($matches) {
				$line = $matches[0];
				$blocks = explode("\t", $line);
				$line = $blocks[0];
				unset($blocks[0]);
				foreach ($blocks as $block) {
					if ($matches['space_before'] === ' ')
					{
						$amount = 4;
					}
					else
					{
						// @todo set tab amount of spaces option
						$amount = 4 - mb_strlen($line, 'UTF-8') % 4;
					}
					$line .= str_repeat(" ", $amount) . $block;
				}
				return $line;
			},
			$text
		);
	}
}