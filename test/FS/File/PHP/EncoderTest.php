<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of FS\File\PHP\Encoder.
 *	@package		Tests.FS.File.PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\PHP;

use CeusMedia\Common\FS\File\PHP\Encoder;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS\File\PHP\Encoder.
 *	@package		Tests.FS.File.PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class EncoderTest extends BaseCase
{
	protected string $path;

	public function setUp(): void
	{
		$this->path	= sys_get_temp_dir().'/'.uniqid( 'EncoderTest' );
		mkdir( $this->path );
		file_put_contents( $this->path.'/test.php', "<?php\necho 'test';\n" );
	}

	public function tearDown(): void
	{
		@unlink( $this->path.'/test.php' );
		@unlink( $this->path.'/code.test.php' );
		@rmdir( $this->path );
	}

	public function testEncodeAndDecodeFile(): void
	{
		$encoder	= new Encoder();
		self::assertFalse( $encoder->isEncoded( $this->path.'/test.php' ) );

		self::assertTrue( $encoder->encodeFile( $this->path.'/test.php' ) );
		self::assertTrue( $encoder->isEncoded( $this->path.'/code.test.php' ) );

		unlink( $this->path.'/test.php' );
		self::assertTrue( $encoder->decodeFile( $this->path.'/code.test.php' ) );
		self::assertSame( "<?php\necho 'test';\n", file_get_contents( $this->path.'/test.php' ) );
		self::assertTrue( $encoder->isEncoded( $this->path.'/code.test.php' ) );
	}
}
