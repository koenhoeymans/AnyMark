<?php

namespace AnyMark\Plugins\NewLineStandardizer;

class NewLineStandardizerRegistrarTest extends \PHPUnit\Framework\TestCase
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

        $registrar = new \AnyMark\Plugins\NewLineStandardizer\NewLineStandardizerRegistrar();
        $registrar->registerHandlers($eventDispatcher);
    }
}
