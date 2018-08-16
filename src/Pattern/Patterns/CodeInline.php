<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;

class CodeInline extends Code
{
    public function getRegex() : string
    {
        return
            '@
			(?<!\\\)[`](?<extra_backticks>([`])*)
			(?<code>.+?)
			\g{extra_backticks}[`](?!`)
			@x';
    }

    public function handleMatch(
        array $match,
        \ElementTree\Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ) : ?\ElementTree\Component {
        # if code between backticks starts or ends with code between
        # backticks: remove the spacing
        $code = preg_replace("#^\s*(.+?)\s*$#", "\${1}", $match['code']);

        return $this->createCodeReplacement($code, false, $parent);
    }
}
