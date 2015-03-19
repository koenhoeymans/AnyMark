<?php

namespace Anymark\Pattern\Patterns;

class AutoLinkTest extends \AnyMark\PatternReplacementAssertions
{
    public function setup()
    {
        $this->pattern = new \AnyMark\Pattern\Patterns\AutoLink();
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @test
     */
    public function anEmailAddressIsLinkedWhenPlacedBetweenALesserThanAndGreaterThanSign()
    {
        $a = $this->elementTree()->createElement('a');
        $a->setAttribute('href', "mailto:me@xmpl.com");
        $a->append(new \ElementTree\ElementTreeText("me@xmpl.com"));

        $this->assertEquals(
            $a,
            $this->applyPattern("Mail to <me@xmpl.com>.")
        );
    }

    /**
     * @test
     */
    public function withoutAngledBracketsNoMailLinkIsCreated()
    {
        $text = "Mail to me@example.com, it's an email address link.";
        preg_match($this->getPattern()->getRegex(), $text, $match);
        $this->assertTrue(empty($match));
    }

    /**
     * @test
     */
    public function anUrlBetweenLesserThanAndGreaterThanSignIsAutolinked()
    {
        $a = $this->elementTree()->createElement('a');
        $a->setAttribute('href', "http://example.com");
        $a->append(new \ElementTree\ElementTreeText("http://example.com"));

        $this->assertEquals(
            $a,
            $this->applyPattern("Visit <http://example.com>.")
        );
    }

    /**
     * @test
     */
    public function specialEmail()
    {
        $a = $this->elementTree()->createElement('a');
        $a->setAttribute('href', "mailto:abc+mailbox/department=shipping@example.com");
        $a->append(new \ElementTree\ElementTreeText("abc+mailbox/department=shipping@example.com"));

        $this->assertEquals(
            $a,
            $this->applyPattern("Visit <abc+mailbox/department=shipping@example.com>.")
        );
    }

    /**
     * @test
     */
    public function specialEmail_2()
    {
        $a = $this->elementTree()->createElement('a');
        $a->setAttribute('href', "mailto:!#$%&'*+-/=?^_`.{|}~@example.com");
        $a->append(new \ElementTree\ElementTreeText("!#$%&'*+-/=?^_`.{|}~@example.com"));

        $this->assertEquals(
            $a,
            $this->applyPattern("Visit <!#$%&'*+-/=?^_`.{|}~@example.com>.")
        );
    }

    /**
     * @test
     */
    public function specialEmail_3()
    {
        $a = $this->elementTree()->createElement('a');
        $a->setAttribute('href', "mailto:\"abc@def\"@example.com");
        $a->append(new \ElementTree\ElementTreeText("\"abc@def\"@example.com"));

        $this->assertEquals(
            $a,
            $this->applyPattern("Visit <\"abc@def\"@example.com>.")
        );
    }
}
