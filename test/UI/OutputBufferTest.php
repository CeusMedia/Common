<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of UI_Template
 *	@package		tests.ui
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI;

use CeusMedia\Cache\Util\FileLock;
use CeusMedia\Common\UI\OutputBuffer;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of UI_Template
 *	@package		tests.ui
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class OutputBufferTest extends BaseCase
{
	public function testConstruct(): void
	{
		$buffer = new OutputBuffer( FALSE );
		self::assertFalse( $buffer->isOpen() );

		$buffer = new OutputBuffer();
		self::assertTrue( $buffer->isOpen() );
		$buffer->close();
		self::assertFalse( $buffer->isOpen() );
	}

	public function testClose(): void
	{
		$buffer = new OutputBuffer();
		self::assertTrue( $buffer->isOpen() );
		$buffer->close();
		self::assertFalse( $buffer->isOpen() );
	}

	public function testClose_alreadyClosed_willThrow(): void
	{
		$this->expectException( \RuntimeException::class);
		$buffer = new OutputBuffer();
		$buffer->close();
		$buffer->close();
	}

	public function testClear(): void
	{
		$buffer = new OutputBuffer();
		echo "test";
		self::assertTrue( $buffer->has() );
		$buffer->clear();
		self::assertFalse( $buffer->has() );
		$buffer->close();
		$buffer->clear();
	}

	public function testFlush(): void
	{
		ob_start();
		$buffer = new OutputBuffer();
		print( 'Test Content' );
		self::assertSame( 'Test Content', $buffer->get() );
		$buffer->flush();
		self::assertSame( '', $buffer->get() );
		self::assertTrue( $buffer->isOpen() );
		$buffer->flush();
		$buffer->close();
		self::assertSame( 'Test Content', ob_get_clean() );
	}

	public function testFlushAndClose(): void
	{
		ob_start();
		$buffer = new OutputBuffer();
		print( 'Test Content' );
		self::assertSame( 'Test Content', $buffer->get() );
		$buffer->flushAndClose();
		self::assertFalse( $buffer->isOpen() );
		self::assertSame( 'Test Content', ob_get_clean() );
		$buffer->open();
		self::assertSame( '', $buffer->get() );
		$buffer->close();
	}

	public function testGet(): void
	{
		$buffer = new OutputBuffer();
		self::assertSame( '', $buffer->get() );
		echo "test";
		self::assertSame( 'test', $buffer->get() );

		echo "test";
		self::assertSame( 'testtest', $buffer->get( TRUE ) );
		self::assertSame( '', $buffer->get() );
		$buffer->close();
	}

	public function testGetAndClose(): void
	{
		$buffer = new OutputBuffer();
		self::assertSame( '', $buffer->get() );
		echo "test";
		self::assertSame( 'test', $buffer->get() );
		self::assertTrue( $buffer->isOpen() );
		self::assertSame( 'test', $buffer->getAndClose() );
		self::assertFalse( $buffer->isOpen() );
		$buffer->open();
		self::assertSame( '', $buffer->get() );
		self::assertSame( '', $buffer->getAndClose() );
		self::assertFalse( $buffer->isOpen() );
	}

	public function testOpen(): void
	{
		$buffer = new OutputBuffer( FALSE );
		self::assertFalse( $buffer->isOpen() );
		$buffer->open();
		self::assertTrue( $buffer->isOpen() );
		$buffer->close();
	}

	public function testOpen_alreadyOpen_willThrow(): void
	{
		$this->expectException( \RuntimeException::class);
		$buffer = new OutputBuffer();
		$buffer->open();
	}

	protected  function setUp(): void
	{
	}
}