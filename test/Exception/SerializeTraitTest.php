<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_Lock.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Exception;

use CeusMedia\Common\Exception\Runtime;
use CeusMedia\CommonTest\BaseCase;
use Exception;

class SerializeTraitTest extends BaseCase
{
	public function testSerialize(): void
	{
		$e = Runtime::create( 'mesg1', 1 );
		$facts	= $e->__serialize();

		self::assertIsArray( $facts );
		self::assertArrayHasKey( 'message', $facts );
		self::assertArrayHasKey( 'code', $facts );
		self::assertArrayHasKey( 'file', $facts );
		self::assertArrayHasKey( 'line', $facts );

		self::assertArrayHasKey( 'class', $facts );
		self::assertArrayHasKey( 'type', $facts );

		self::assertEquals( 'Runtime', $facts['type'] );

		self::assertArrayHasKey( 'class', $facts );
		self::assertEquals( Runtime::class, $facts['class'] );
	}

	public function testSerialize_withPrevious(): void
	{
		$e	= new Exception( 'Original test exception' );

		$e = Runtime::create( 'mesg1', 1, $e );
		$facts	= $e->__serialize();

		self::assertIsArray( $facts );
		self::assertArrayHasKey( 'previous', $facts );
		self::assertIsObject( $facts['previous'] );
		self::assertInstanceOf( \Throwable::class,  $facts['previous'] );
		self::assertEquals( 'Original test exception', $facts['previous']->getMessage() );
	}

	public function testSerialize_withDescriptiveTrait(): void
	{
		$e = Runtime::create( 'mesg1', 1 )
			->setDescription( 'desc1' )
			->setSuggestion( 'sugg1' );
		$facts	= $e->__serialize();

		self::assertArrayHasKey( 'description', $facts );
		self::assertEquals( 'desc1', $facts['description'] );

		self::assertArrayHasKey( 'description', $facts );
		self::assertEquals( 'sugg1', $facts['suggestion'] );
	}

	public function testGetAdditionalProperties(): void
	{
		$e = Runtime::create( 'mesg1', 1 )
			->setDescription( 'desc1' )
			->setSuggestion( 'sugg1' );
		$result	= $e->getAdditionalProperties();
		self::assertEquals( [
			'description'	=> 'desc1',
			'suggestion'	=> 'sugg1',
			'format'		=> 0,
		], $result );
	}
}



