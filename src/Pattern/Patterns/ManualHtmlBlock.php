<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

class ManualHtmlBlock extends Pattern
{
    protected $blockTags = 'address|article|aside|audio|blockquote|canvas|dd|del|div|dl
			|fieldset|figcaption|figure|footer|form|h1|h2|h3|h4|h5|h6|header|hgroup
			|hr|ins|noscript|ol|output|p|pre|section|table|tfoot|ul|video';

    protected $insDelTags = 'ins|del';

    protected $attributes = '(\s+\w+(=(?:\"[^\"]*?\"|\'[^\']*?\'|[^\'\">\s]+))?)*';

    public function getRegex() : string
    {
        return '
		@(?J)
		(?<=^|\n)
		(

		<!--(?<comment>(\n|.)*?)-->

		|

		<(?<name>'.$this->blockTags.')(?<attributes>'.$this->attributes.')>
		(?<content>
			(
			([ ]{4}|\t)+.+\n
			|
			<(\w+)'.$this->attributes.'[ ]?/>
			|
			(?<subpattern>
				<(?<subname>\w+)'.$this->attributes.'>(?&content)?</\g{subname}>
			)
			|
			[^<]
			)+
		)?
		(?<!\t|[ ]{4})</\g{name}>(?=\s|$)

		|

		<(?<name>br|div|hr)(?<attributes>'.$this->attributes.')[ ]?/?>

		)
		@x';
    }

    public function handleMatch(
        array $match,
        Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ) : ?\ElementTree\Component {
        if (!empty($match['comment'])) {
            return $this->createComment($match['comment']);
        }
        if (($match['name'] === 'ins' || $match['name'] === 'del')
            && substr($match['content'], 0, 1) !== "\n") {
            return null;
        }

        $element = $this->createElement($match['name']);
        if (!empty($match['content'])) {
            $element->append(
                $this->createText($match['content'])
            );
        }

        $attributes = $this->getAttributes($match['attributes']);
        foreach ($attributes['name'] as $key => $value) {
            $attr = $element->setAttribute(
                $value,
                $attributes['value'][$key]
            );
            if ($attributes['quote'][$key] === "'") {
                $attr->singleQuotes();
            }
        }

        return $element;
    }

    private function getAttributes($tagPart)
    {
        preg_match_all(
            "@
			[ \n]*
			(?<name>\w+)
			(
			=
			((?<quote>[\"|'])(?<value>.*?)[\"|'])
			)?
			@x",
            $tagPart,
            $matches
        );

        return $matches;
    }
}
