<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Resizing Images.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\Image;

use InvalidArgumentException;
use Exception;
use RuntimeException;

/**
 *	Resizing Images.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class ThumbnailCreator
{
	/**	@var	int			$quality		Quality of Target Image */
	private $quality;

	/**	@var	array		$size			Sizes of Source Image */
	private $size	= [];

	/**	@var	string		$source			Source File Name of Source Image */
	private $source;

	/**	@var	string		$target			Target File Name of Target Image */
	private $target;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$source 		File Name of Source Image
	 *	@param		string	$target 		File Name of Target Image
	 *	@param		int		$quality 		Quality of Target Image
	 *	@return		void
	 */
	public function __construct( string $source, string $target, int $quality = 100 )
	{
		$this->setSource( $source );
		$this->setTarget( $target );
		$this->setQuality( $quality );
	}

	/**
	 *	Sets the Quality of resulting Image.
	 *	@access		public
	 *	@param		int		$quality 	Quality of resulting Image
	 *	@return		self
	 */
	public function setQuality( int $quality ): self
	{
		$this->quality	= $quality;
        return $this;
	}

	/**
	 *	Sets the File Name of Source Image.
	 *	@access		public
	 *	@param		string	$source 		File Name of Source Image
	 *	@return		self
	 */
	public function setSource( string $source ): self
	{
		if( !file_exists( $source ) )
			throw new InvalidArgumentException( 'Image File "'.$source.'" is not existing.' );
		$size = @getimagesize( $source );
		if( !$size )
			throw new Exception( 'Source File "'.$source.'" is not a supported Image.' );
		$this->size		= $size;
		$this->source	= $source;
		return $this;
	}

	/**
	 *	Sets the File Name of Target Image.
	 *	@access		public
	 *	@param		string	$target 		File Name of resulting Target Image
	 *	@return		self
	 */
	public function setTarget( string $target ): self
	{
		$this->target	= $target;
		return $this;
	}

	/**
	 *	Resizes Image to a given Size.
	 *	@access		public
	 *	@param		int			$width 		Width of Target Image
	 *	@param		int			$height 	Height of Target Image
	 *	@return		bool
	 */
	public function thumbize( int $width, int $height ): bool
	{
		if( !count( $this->size ) )
			throw new RuntimeException( 'No Source Image set.' );

		$thumb	= imagecreatetruecolor( $width, $height );
		if( function_exists( 'imageantialias' ) )
			imageantialias( $thumb, TRUE );

		switch( $this->size[2] ){
			case IMAGETYPE_GIF:
				$source	= imagecreatefromgif( $this->source );
				imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $width, $height, $this->size[0], $this->size[1] );
				imagegif( $thumb, $this->target );
				break;
			case IMAGETYPE_JPEG:
				$source	= imagecreatefromjpeg( $this->source );
				imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $width, $height, $this->size[0], $this->size[1] );
				imagejpeg( $thumb, $this->target, $this->quality );
				break;
			case IMAGETYPE_PNG:
				$source	= imagecreatefrompng( $this->source );
				imagecopyresampled( $thumb, $source, 0, 0, 0, 0, $width, $height, $this->size[0], $this->size[1] );
				imagepng( $thumb, $this->target );
				break;
			default:
				throw new Exception( 'Image Type is no supported.' );
		}
		return TRUE;
	}

	/**
	 *	Resizes Image within a given Limit.
	 *	@access		public
	 *	@param		int			$width 		Largest Width of Target Image
	 *	@param		int			$height 	Largest Height of Target Image
	 *	@return		bool
	 */
	public function thumbizeByLimit( int $width, int $height ): bool
	{
		if( !count( $this->size ) )
			throw new RuntimeException( 'No Source Image set.' );

		$ratio_s	= $this->size[0] / $this->size[1];
		$ratio_t	= $width / $height;
		if( ( $ratio_s / $ratio_t ) > 1 )
			$height	= ceil( $width / $ratio_s );
		else
			$width	= ceil( $height * $ratio_s );
		return $this->thumbize( $width, $height );
	}
}
