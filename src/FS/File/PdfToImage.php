<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	File permission data object and handler.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use Imagick;
use ImagickException;

/**
 *	Converts PDF files to image files.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class PdfToImage
{
	protected ?Imagick $im			= NULL;
	protected string $outputFormat	= 'png';

	/**
	 *	@param		string|NULL		$fileName
	 *	@throws		ImagickException
	 */
	public function __construct( ?string $fileName = NULL )
	{
		if( $fileName )
			$this->read( $fileName );
	}

	/**
	 * @param string $fileName
	 * @param int $page
	 * @return $this
	 * @throws ImagickException
	 */
	public function read( string $fileName, int $page = 0 ): self
	{
		$this->im = new Imagick();
		$this->im->readImage( $fileName.'['.$page.']' );
		$this->im->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
		$this->im->setImageFormat( $this->outputFormat );
		$this->im->setResolution(250, 250);
		return $this;
	}

	/**
	 * @param string $format
	 * @return $this
	 * @throws ImagickException
	 */
	public function setOutputFormat( string $format = 'png' ): self
	{
		$this->im->setImageFormat( $format );
		$this->outputFormat	= $format;
		return $this;
	}

	/**
	 *	@param		int		$width
	 *	@param		int		$height
	 *	@return		self
	 *	@throws		ImagickException
	 */
	//
	public function setSize( int $width, int $height ): self
	{
		$this->im->thumbnailImage( $width, $height );
		return $this;
	}

	/**
	 *	@param		string		$fileName		Target image file name
	 *	@return		void
	 *	@throws		ImagickException
	 */
	public function write( string $fileName ): void
	{
		$this->im->writeImage( $fileName );
	}

	/**
	 *	@param		string			$sourceFile
	 *	@param		string			$targetFile
	 *	@param		int				$width
	 *	@param		int				$height
	 *	@param		string|NULL		$format
	 *	@return		void
	 *	@throws		ImagickException
	 */
	public static function convert( string $sourceFile, string $targetFile, int $width, int $height, ?string $format = NULL ): void
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
