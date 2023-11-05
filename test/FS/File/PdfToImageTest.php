<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_PdfToImage.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\PdfToImage;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_PdfToImage.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PdfToImageTest extends BaseCase
{
	public function testConvert()
	{
		$sourceFile		= __DIR__.'/test1.pdf';
		$targetFile		= __DIR__.'/test1.pdf.png';

		PdfToImage::convert( $sourceFile, $targetFile, 800, 0 );
		self::assertFileExists( $targetFile );
		@unlink( $targetFile );
	}

	public function testWrite()
	{
		$sourceFile		= __DIR__.'/test1.pdf';
		$targetFile		= __DIR__.'/test1.pdf.png';

		$converter	= new PdfToImage();
		$converter->read( $sourceFile );
		$converter->setSize( 800, 0 );
		$converter->write( $targetFile );
		self::assertFileExists( $targetFile );
		@unlink( $targetFile );
	}
}