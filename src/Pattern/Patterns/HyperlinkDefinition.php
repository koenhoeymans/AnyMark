<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

/**
 * @package AnyMark
 */
class HyperlinkDefinition extends Pattern
{
    private $linkDefinitions = array();

    public function getRegex()
    {
        return
            '@
			(?<=^|\n)
			[ ]{0,3}							# new line, 0-3 spaces
			(\[(?<id>.+)\]):[ ]+ 					# id:space
			(<(?<url1>.+)>|(?<url2>\S+))			# url or <url>
			(										# "title"|\'title\'|(title)
			\n?[\t ]*								# options: on new line, indented
			("|\'|\()
			(?<title>.+)
			("|\'|\))
			)?
			(?=\n|$)
			@x';
    }

    public function handleMatch(
        array $match, Element $parent = null, Pattern $parentPattern = null
    ) {
        $this->save($match);

        return $this->createText('');
    }

    private function save($definition)
    {
        $id = $definition['id'];
        $url = ($definition['url1']) ?: $definition['url2'];
        $title = isset($definition['title']) ? $definition['title'] : null;
        $this->linkDefinitions[$id] =
        new \AnyMark\Pattern\Patterns\LinkDefinition($id, $url, $title);
    }

    /**
     * Returns a link definition based on reference.
     *
     * @param  string                                  $linkDefinition
     * @return AnyMark\Pattern\Patterns\LinkDefinition
     */
    public function get($linkDefinition)
    {
        if (!isset($this->linkDefinitions[$linkDefinition])) {
            return;
        }

        return $this->linkDefinitions[$linkDefinition];
    }
}