<?php

namespace AnyMark\Plugins\LinkDefinitionCollector;

use AnyMark\Pattern\Patterns\LinkDefinition;

class LinkDefinitionCollector
{
    private $linkDefinitions = array();

    public function process(string $text): string
    {
        return preg_replace_callback(
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
			@x',
            array($this, 'save'),
            $text
        );
    }

    private function save($definition)
    {
        $id = $definition['id'];
        $url = ($definition['url1']) ?: $definition['url2'];
        $title = isset($definition['title']) ? $definition['title'] : null;
        $this->linkDefinitions[$id] =
            new \AnyMark\Pattern\Patterns\LinkDefinition($id, $url, $title);

        return $definition[0];
    }

    /**
     * Returns a link definition based on reference.
     */
    public function get(string $linkDefinition): ?LinkDefinition
    {
        if (!isset($this->linkDefinitions[$linkDefinition])) {
            return null;
        }

        return $this->linkDefinitions[$linkDefinition];
    }
}
