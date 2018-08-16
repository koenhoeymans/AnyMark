<?php

namespace AnyMark\Plugins\Detab;

class DetabRegistrarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function registersForBeforeParsingEvent()
    {
        $eventDispatcher = $this->createMock('Epa\\Api\\EventDispatcher');
        $eventDispatcher
            ->expects($this->once())
            ->method('registerForEvent')
            ->with(
                'AnyMark\\Api\\BeforeParsingEvent',
                function () {
                }
            );

        $registrar = new \AnyMark\Plugins\Detab\DetabRegistrar();
        $registrar->registerHandlers($eventDispatcher);
    }
}
