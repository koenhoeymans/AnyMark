<?php

namespace AnyMark\Plugins\EmailObfuscator;

class EmailObfuscatorRegistrarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function registersForAfterParsingEvent()
    {
        $eventDispatcher = $this->createMock('Epa\\Api\\EventDispatcher');
        $eventDispatcher
            ->expects($this->once())
            ->method('registerForEvent')
            ->with(
                'AnyMark\\Api\\AfterParsingEvent',
                function () {
                }
            );

        $registrar = new \AnyMark\Plugins\EmailObfuscator\EmailObfuscatorRegistrar();
        $registrar->registerHandlers($eventDispatcher);
    }
}
