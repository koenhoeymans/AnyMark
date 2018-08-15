<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

abstract class Code extends Pattern
{
    protected function createCodeReplacement($code, $pre = true, Element $parent = null) : Element
    {
        $code = htmlentities($code, ENT_NOQUOTES);
        $codeElement = $this->createElement('code');
        $codeElement->append($this->createText($code));

        if ($pre) {
            $preElement = $this->createElement('pre');
            $preElement->append($codeElement);

            return $preElement;
        } else {
            return $codeElement;
        }
    }
}
