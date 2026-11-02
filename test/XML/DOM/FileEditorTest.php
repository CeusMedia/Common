<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML\DOM\FileEditor.
 *	@package		Tests.XML.DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\DOM;

use CeusMedia\Common\XML\DOM\FileEditor;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of XML\DOM\FileEditor.
 *	@package		Tests.XML.DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class FileEditorTest extends BaseCase
{
	protected string $fileName;

	public function setUp(): void
	{
		$this->fileName	= sys_get_temp_dir().'/'.uniqid( 'FileEditorTest' ).'.xml';
		file_put_contents( $this->fileName, '<?xml version="1.0"?><root><item>a</item><other>x</other><item>b</item><sub><x>1</x><x>2</x></sub></root>' );
	}

	public function tearDown(): void
	{
		@unlink( $this->fileName );
	}

	public function testRemoveNode(): void
	{
		$editor	= new FileEditor( $this->fileName );
		self::assertTrue( $editor->removeNode( 'item[1]' ) );
		self::assertTrue( $editor->removeNode( 'sub/x[0]' ) );

		$xml	= simplexml_load_file( $this->fileName );
		self::assertSame( 'root', $xml->getName() );
		self::assertCount( 1, $xml->item );
		self::assertSame( 'a', (string) $xml->item[0] );
		self::assertSame( 'x', (string) $xml->other );
		self::assertCount( 1, $xml->sub->x );
		self::assertSame( '2', (string) $xml->sub->x[0] );
	}

	public function testEditNestedNodeKeepsTree(): void
	{
		$editor	= new FileEditor( $this->fileName );
		self::assertTrue( $editor->editNodeContent( 'sub/x', '3' ) );

		$xml	= simplexml_load_file( $this->fileName );
		self::assertSame( 'root', $xml->getName() );
		self::assertCount( 2, $xml->item );
		self::assertSame( '3', (string) $xml->sub->x[0] );
	}
}
