<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS\File\CSV;

use CeusMedia\Common\FS\File\CSV\Writer;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS\File\CSV\Writer.
 *	@package		Tests.FS.File.CSV
 */
class WriterTest extends BaseCase
{
	protected string $fileName;

	public function setUp(): void
	{
		$this->fileName	= sys_get_temp_dir().'/CeusMediaCommonTestCsv'.getmypid().'.csv';
	}

	public function tearDown(): void
	{
		@unlink( $this->fileName );
	}

	public function testWrite(): void
	{
		$data	= [
			['plain', 'with,comma', 'say "hi"'],
			['"quoted"', "multi\nline", 'back\\slash'],
		];
		( new Writer( $this->fileName ) )->write( $data, ['a', 'b', 'c,d'] );
		$content	= (string) file_get_contents( $this->fileName );
		self::assertStringStartsWith( 'a,b,"c,d"'."\n".'plain,"with,comma","say ""hi"""', $content );

		$handle	= fopen( $this->fileName, 'r' );
		$rows		= [];
		while( FALSE !== ( $row = fgetcsv( $handle, NULL, ',', '"', '' ) ) )
			$rows[]	= $row;
		fclose( $handle );
		self::assertSame( ['a', 'b', 'c,d'], $rows[0] );
		self::assertSame( $data, array_slice( $rows, 1 ) );
	}
}
