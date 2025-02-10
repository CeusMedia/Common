<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_Lock.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Exception;

use CeusMedia\Common\Exception\Runtime;
use CeusMedia\Common\Exception\Traits\Descriptive as DescriptiveTrait;
use CeusMedia\Common\Exception\Traits\Jsonable as JsonableTrait;
use CeusMedia\CommonTest\BaseCase;
use Exception;

class JsonTraitTest extends BaseCase
{
	public function testJson(): void
	{
		$e = Runtime::create( 'mesg1', 1 );

		$json	= $e->getJson();
		$actual	= json_decode( $json, FALSE );

		self::assertObjectHasProperty( 'message', $actual );
		self::assertEquals( 'mesg1', $actual->message );

		self::assertObjectHasProperty( 'type', $actual );
		self::assertEquals( 'Runtime', $actual->type );

		self::assertObjectHasProperty( 'class', $actual );
		self::assertEquals( Runtime::class, $actual->class );
	}

	public function testJson_WithBasicException(): void
	{
		$e = new JsonableButNotSerializableException( 'mesg1', 1 );

		$json	= $e->getJson();
		$actual	= json_decode( $json, FALSE );

		self::assertObjectHasProperty( 'message', $actual );
		self::assertEquals( 'mesg1', $actual->message );

		self::assertObjectHasProperty( 'type', $actual );
		self::assertEquals( 'JsonableButNotSerializableException', $actual->type );

		self::assertObjectHasProperty( 'class', $actual );
		self::assertEquals( JsonableButNotSerializableException::class, $actual->class );
	}

	public function testJson_WithBasicException_withPrevious(): void
	{
		$e	= Runtime::create( 'Original test exception' );
		$e = new JsonableButNotSerializableException( 'mesg1', 1, $e );

		$json	= $e->getJson();
		$actual	= json_decode( $json, FALSE );

		self::assertObjectHasProperty( 'previous', $actual );
		self::assertIsObject( $actual->previous );
		self::assertEquals( 'Original test exception', $actual->previous->message );
	}

	public function testJson_WithBasicException_withDescriptiveTrait(): void
	{
		$e = new JsonableButNotSerializableException( 'mesg1', 1 );
		$e->setDescription( 'desc1' );
		$e->setSuggestion( 'sugg1' );

		$json	= $e->getJson();
		$actual	= json_decode( $json, FALSE );

		self::assertObjectHasProperty( 'description', $actual );
		self::assertEquals( 'desc1', $actual->description );

		self::assertObjectHasProperty( 'description', $actual );
		self::assertEquals( 'sugg1', $actual->suggestion );
	}

	public function testJson_withPrevious(): void
	{
		$e	= Runtime::create( 'Original test exception' );
		$e	= Runtime::create( 'mesg1', 1, $e );

		$json	= $e->getJson();
		$actual	= json_decode( $json, FALSE );

		self::assertObjectHasProperty( 'previous', $actual );
		self::assertIsObject( $actual->previous );
		self::assertEquals( 'Original test exception', $actual->previous->message );
	}

	public function testJson_withDescriptiveTrait(): void
	{
		$e = Runtime::create( 'mesg1', 1 )
			->setDescription( 'desc1' )
			->setSuggestion( 'sugg1' );

		$json	= $e->getJson();
		$actual	= json_decode( $json, FALSE );

		self::assertObjectHasProperty( 'message', $actual );
		self::assertEquals( 'mesg1', $actual->message );

		self::assertObjectHasProperty( 'description', $actual );
		self::assertEquals( 'desc1', $actual->description );

		self::assertObjectHasProperty( 'description', $actual );
		self::assertEquals( 'sugg1', $actual->suggestion );

		self::assertObjectHasProperty( 'type', $actual );
		self::assertEquals( 'Runtime', $actual->type );

		self::assertObjectHasProperty( 'class', $actual );
		self::assertEquals( Runtime::class, $actual->class );
	}
}

class JsonableButNotSerializableException extends Exception
{
	use JsonableTrait;
	use DescriptiveTrait;
}