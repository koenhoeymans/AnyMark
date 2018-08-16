<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use AnyMark\Plugins\LinkDefinitionCollector\LinkDefinitionCollector;
use ElementTree\Element;

class Image extends Pattern
{
    private $linkDefinitions;

    public function __construct(LinkDefinitionCollector $linkDefinitionCollector)
    {
        $this->linkDefinitions = $linkDefinitionCollector;
    }

    public function getRegex() : string
    {
        return
            '@

			(?<inline>(?J)
				!\[(?<alt>.*)\]							# ![alternate text]
				\(										# (
					(?<path>							# path|<path>
						<(\S+?)?>
						|
						(\S+?)?
					)
					([ ]+("|\')(?<title>.+)("|\')[ ]*)?	# "optional title"
				\)										# )
			)

			|

			(?<reference>(?J)
				(?<begin>^|[ ]+)
				!\[(?<alt>.+)\]					# ![alternate text]
				\[(?<id>.+)\]					# [id]
				(?<end>[ ]+|$)
			)

			@xU';
    }

    public function handleMatch(
        array $match,
        Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ) : ?\ElementTree\Component {
        if (isset($match['reference'])) {
            return $this->replaceReference($match);
        } else {
            return $this->replaceInline($match);
        }
    }

    private function replaceInline(array $match)
    {
        $path = str_replace('"', '&quot;', $match['path']);
        if (isset($path[0]) && $path[0] === '<') {
            $path = substr($path, 1, -1);
        }

        $img = $this->createElement('img');
        $img->setAttribute('src', $path);
        $img->setAttribute('alt', $match['alt']);
        if (isset($match['title'])) {
            $img->setAttribute('title', $match['title']);
        }

        return $img;
    }

    /**
     * @todo replace circular handling
     */
    private function replaceReference(array $match)
    {
        $linkDefinition = $this->linkDefinitions->get($match['id']);
        if (!$linkDefinition) {
            throw new \Exception('Following link definition not found: "[' .$match['id'].']"');
        }
        $title = $linkDefinition->getTitle();

        $img = $this->createElement('img');
        $img->setAttribute('src', $linkDefinition->getUrl());
        $img->setAttribute('alt', $match['alt']);
        if ($title) {
            $img->setAttribute('title', $title);
        }

        return $img;
    }
}
