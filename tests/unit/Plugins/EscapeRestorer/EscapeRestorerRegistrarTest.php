<?php

namespace AnyMark\Plugins\EscapeRestorer;

class EscapeRestorerRegistrarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function registersForAfterParsingEvent()
    {
        $eventDispatcher = $this->getMock('Epa\\Api\\EventDispatcher');
        $eventDispatcher
            ->expects($this->at(0))
            ->method('registerForEvent')
            ->with(
                'AnyMark\\PublicApi\\AfterParsingEvent',
                function () {
                }
            );

        $registrar = new \AnyMark\Plugins\EscapeRestorer\EscapeRestorerRegistrar();
        $registrar->registerHandlers($eventDispatcher);
    }

    /**
     * @test
     */
    public function registersForPatternMatch()
    {
        $eventDispatcher = $this->getMock('Epa\\Api\\EventDispatcher');
        $eventDispatcher
            ->expects($this->at(1))
            ->method('registerForEvent')
            ->with(
                'AnyMark\\PublicApi\\PatternMatch',
                function () {
                }
            );

        $registrar = new \AnyMark\Plugins\EscapeRestorer\EscapeRestorerRegistrar();
        $registrar->registerHandlers($eventDispatcher);
    }
}
