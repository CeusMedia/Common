<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\FS\File;

use Imagick;

class PdfToImage
{
	protected $im;
	protected $outputFormat	= 'png';

	public function __construct( ?string $fileName = NULL )
	{
		if( $fileName )
			$this->read( $fileName );
	}

	public function read( string $fileName, int $page = 0 )
	{
		$this->im = new Imagick();
		$this->im->readImage( $fileName.'['.$page.']' );
		$this->im->setImageFormat( $this->outputFormat );
	}

	public function setOutputFormat( string $format = 'png' )
	{
		$this->im->setImageFormat( $format );
		$this->outputFormat	= $format;
	}

	//set the resolution of the resulting jpg
	public function setSize( int $width, int $height )
	{
		$this->im->thumbnailImage( $width, $height );
	}

	public function write( string $fileName )
	{
		$this->im->writeImage( $fileName );
	}

	public static function convert( string $sourceFile, string $targetFile, int $width, int $height, ?string $format = NULL )
	{
		$instance	= new self( $sourceFile );
		$instance->setSize( $width, $height );
		if( $format )
			$instance->setOutputFormat( $format );
		$instance->write( $targetFile );
	}
}

/* DEMO

$im	= new PdfToImage();
$im->read( 'input.pdf', $page = 0 );
$im->setSize( 256, 0 );
$im->write( 'output.png' );

*/
