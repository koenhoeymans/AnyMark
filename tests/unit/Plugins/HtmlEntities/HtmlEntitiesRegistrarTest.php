<?php

namespace AnyMark\Plugins\HtmlEntities;

class HtmlEntitiesRegistrarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function registersCallbackForAfterParsingEvent()
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

        $registrar = new \AnyMark\Plugins\HtmlEntities\HtmlEntitiesRegistrar();
        $registrar->registerHandlers($eventDispatcher);
    }
}
