<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of ADT\Bitmask.
 *	@package		Tests.ADT.Event
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT\Event;

use CeusMedia\Common\ADT\Event\Callback;
use CeusMedia\Common\ADT\Event\Data as EventData;
use CeusMedia\Common\ADT\Event\Handler as EventHandler;
use CeusMedia\CommonTest\BaseCase;
use InvalidArgumentException;

/**
 *	TestUnit of ADT\Bitmask.
 *	@package		Tests.ADT.Event
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class HandlerTest extends BaseCase
{
	protected EventHandler $handler;

	public function setUp(): void
	{
		$this->handler	= new EventHandler();
	}

	public function testBind(): void
	{
		self::assertEquals( 1, $this->handler->bind( 'test1', function(){} ) );
		self::assertEquals( 2, $this->handler->bind( 'test1', new Callback( function(){} ) ) );
	}

	public function testTrigger_empty(): void
	{
		self::assertEquals( 0, $this->handler->trigger( 'test0' ) );
	}
	public function testTrigger_emptyCallback(): void
	{
		$function1	= function( $event ){};
		$callback1	= new Callback( $function1 );
		self::assertEquals( 1, $this->handler->bind('test1', $callback1 ) );
		self::assertEquals( 1, $this->handler->trigger( 'test1' ) );
	}
	public function testTrigger_withData(): void
	{
		$data		= (object) ['integer' => 1];
		$function2	= function( EventData $event ){ $event->data->integer *= 2; };
		$callback2	= new Callback( $function2, $data );
		self::assertEquals( 1, $this->handler->bind( 'test2', $callback2 ) );
		self::assertEquals( 1, $this->handler->trigger( 'test2', $this ) );
		self::assertEquals( 2, $data->integer );
	}

	public function testTrigger_withStop(): void
	{
		$data		= (object) ['integer' => 1];
		$function2	= function( EventData $event ){
			$event->data->integer *= 2;
			$event->stop();
		};
		$callback2	= new Callback( $function2, $data );
		self::assertEquals( 1, $this->handler->bind( 'test2', $callback2 ) );
		self::assertEquals( 2, $this->handler->bind( 'test2', $callback2 ) );
		self::assertEquals( 3, $this->handler->bind( 'test2', $callback2 ) );
		self::assertEquals( 1, $this->handler->trigger( 'test2', $this ) );
		self::assertEquals( 2, $data->integer );

		self::assertEquals( 1, $this->handler->trigger( 'test2', $this ) );
		/** @noinspection PhpConditionAlreadyCheckedInspection */
		self::assertEquals( 4, $data->integer );
	}

	public function testStopEvent(): void
	{
		$data		= (object) ['integer' => 1];
		$function	= function( EventData $event ){ $event->data->integer *= 2; };
		self::assertEquals( 1, $this->handler->bind( 'testStop', new Callback( $function, $data ) ) );
		self::assertEquals( 1, $this->handler->trigger( 'testStop' ) );
		self::assertTrue( $this->handler->stopEvent( 'testStop' ) );
		self::assertEquals( 1, $this->handler->trigger( 'testStop' ) );
		self::assertFalse( $this->handler->stopEvent( 'testStopInvalidName' ) );
	}
}