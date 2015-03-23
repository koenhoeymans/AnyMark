<?php

namespace AnyMark\Plugins\HtmlEntities;

class HtmlEntitiesRegistrarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function registersCallbackForAfterParsingEvent()
    {
        $eventDispatcher = $this->getMock('Epa\\Api\\EventDispatcher');
        $eventDispatcher
            ->expects($this->once())
            ->method('registerForEvent')
            ->with(
                'AnyMark\\PublicApi\\AfterParsingEvent',
                function () {
                }
            );

        $registrar = new \AnyMark\Plugins\HtmlEntities\HtmlEntitiesRegistrar();
        $registrar->registerHandlers($eventDispatcher);
    }
}
