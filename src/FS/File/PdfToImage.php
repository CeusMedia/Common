<?php

namespace CeusMedia\Common\FS\File;

use Imagick;

class PdfToImage
{
	protected $im;
	protected $outputFormat	= 'png';

	public function __construct( $fileName = NULL ){
		if( $fileName )
			$this->read( $fileName );
	}

	public function read( $fileName, $page = 0 ){
		$this->im = new Imagick();
		$this->im->readImage( $fileName.'['.$page.']' );
		$this->im->setImageFormat( $this->outputFormat );
	}

	public function setOutputFormat( $format = 'png' ){
		$this->im->setImageFormat( $format );
		$this->outputFormat	= $format;
	}

	//set the resolution of the resulting jpg
	public function setSize( $width, $height ){
		$this->im->thumbnailImage( $width, $height );
	}

	public function write( $fileName = NULL ){
		$this->im->writeImage( $fileName );
	}

	static public function convert( $sourceFile, $targetFile, $width, $height, $format = NULL ){
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
