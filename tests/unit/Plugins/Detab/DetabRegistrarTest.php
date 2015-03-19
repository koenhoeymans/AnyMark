<?php

namespace AnyMark\Plugins\Detab;

class DetabRegistrarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function registersForBeforeParsingEvent()
    {
        $eventDispatcher = $this->getMock('Epa\\Api\\EventDispatcher');
        $eventDispatcher
            ->expects($this->once())
            ->method('registerForEvent')
            ->with('AnyMark\\PublicApi\\BeforeParsingEvent', function () {});

        $registrar = new \AnyMark\Plugins\Detab\DetabRegistrar();
        $registrar->registerHandlers($eventDispatcher);
    }
}
