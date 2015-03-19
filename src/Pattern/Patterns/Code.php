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
abstract class Code extends Pattern
{
    protected function createCodeReplacement($code, $pre = true, Element $parent = null)
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
