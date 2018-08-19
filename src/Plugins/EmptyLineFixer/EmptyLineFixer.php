<?php

namespace AnyMark\Plugins\EmptyLineFixer;

class EmptyLineFixer
{
    public function fix(string $text): string
    {
        return preg_replace("#\n[\t ]+\n#", "\n\n", $text);
    }
}
