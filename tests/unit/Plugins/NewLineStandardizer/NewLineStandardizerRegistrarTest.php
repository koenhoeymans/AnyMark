<?php

namespace AnyMark\Plugins\NewLineStandardizer;

class NewLineStandardizerRegistrarTest extends \PHPUnit\Framework\TestCase
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
            ->with(
                'AnyMark\\PublicApi\\BeforeParsingEvent',
                function () {
                }
            );

        $registrar = new \AnyMark\Plugins\NewLineStandardizer\NewLineStandardizerRegistrar();
        $registrar->registerHandlers($eventDispatcher);
    }
}
