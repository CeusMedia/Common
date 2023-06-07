<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Prints an Image Resource into a File or on Screen.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\Image;

use InvalidArgumentException;
use GdImage;

/**
 *	Prints an Image Resource into a File or on Screen.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Printer
{
	/**	@var		GdImage			$resource		Image Resource */
	protected GdImage $resource;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		GdImage			$resource		Image Resource
	 *	@return		void
	 */
	public function __construct( GdImage $resource )
	{
		$this->resource	= $resource;
	}

	/**
	 *	Writes Image to File.
	 *	@access		public
	 *	@param		string		$fileName		Name of target Image File
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@return		bool
	 */
	public function save( string $fileName, int $type = IMAGETYPE_PNG, int $quality = 100 ): bool
	{
		return $this->saveImage( $fileName, $this->resource, $type, $quality );
	}

	/**
	 *	Saves an Image to File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		Name of target Image File
	 *	@param		GdImage		$resource		Image Resource
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@return		bool
	 */
	public static function saveImage( string $fileName, GdImage $resource, int $type = IMAGETYPE_PNG, int $quality = 100 ): bool
	{
		return match( $type ){
			IMAGETYPE_PNG	=> ImagePNG( $resource, $fileName ),
			IMAGETYPE_JPEG	=> ImageJPEG( $resource, $fileName, $quality ),
			IMAGETYPE_GIF	=> ImageGIF( $resource, $fileName ),
			default			=> throw new InvalidArgumentException( 'Invalid Image Type' ),
		};
	}

	/**
	 *	Print Image on Screen.
	 *	@access		public
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@param		bool		$sendHeader		Flag: set Image MIME Type Header
	 *	@return		bool
	 */
	public function show( int $type = IMAGETYPE_PNG, int $quality = 100, bool $sendHeader = TRUE ): bool
	{
		return $this->showImage( $this->resource, $type, $quality, $sendHeader );
	}

	/**
	 *	Prints an Image to Screen statically.
	 *	@access		public
	 *	@static
	 *	@param		GdImage		$resource		Image Resource
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@param		bool		$sendHeader		Flag: set Image MIME Type Header
	 *	@return		bool
	 */
	public static function showImage( GdImage $resource, int $type = IMAGETYPE_PNG, int $quality = 100, bool $sendHeader = TRUE ): bool
	{
		switch( $type ){
			case IMAGETYPE_GIF:
				if( $sendHeader )
					header( "Content-type: image/gif" );
				return ImageGIF( $resource );
			case IMAGETYPE_JPEG:
				if( $sendHeader )
					header( "Content-type: image/jpeg" );
				return ImageJPEG( $resource, "", $quality );
			case IMAGETYPE_PNG:
				if( $sendHeader )
					header( "Content-type: image/png" );
				return ImagePNG( $resource );
			default:
				throw new InvalidArgumentException( 'Invalid Image Type' );
		}
	}
}
