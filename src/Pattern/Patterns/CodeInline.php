<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

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
        Element $parent = null,
        Pattern $parentPattern = null
    ) : Element {
        # if code between backticks starts or ends with code between
        # backticks: remove the spacing
        $code = preg_replace("#^\s*(.+?)\s*$#", "\${1}", $match['code']);

        return $this->createCodeReplacement($code, false, $parent);
    }
}
