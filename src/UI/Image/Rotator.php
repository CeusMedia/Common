<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Rotates an Image.
 *
 *	Copyright (c) 2009-2024 Christian Würker (ceusmedia.de)
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
 *	@copyright		2009-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\Image;

use RuntimeException;

/**
 *	Rotates an Image.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Rotator extends Modifier
{
	/**
	 *	Inverts Source Image.
	 *	@access		public
	 *	@param		int				$angle			Rotation angle in degrees
	 *	@param		int|NULL		$type			Output format type
	 *	@return		bool
	 */
	public function rotate( int $angle, ?int $type = NULL ): bool
	{
		if( !$this->sourceUri )
			throw new RuntimeException( 'No source image set' );

#		if( function_exists( 'imageantialias' ) )
#			imageantialias( $this->target, TRUE );

		$this->target	= imagerotate( $this->source, $angle, 0 );
		if( $this->targetUri )
			return $this->saveImage( $type );
		return TRUE;
	}

	/**
	 *	Rotates an Image statically.
	 *	@access	public
	 *	@static
	 *	@param		string		$imageUri		URI of Image File
	 *	@param		int			$angle			Rotation angle in degrees
	 *	@param		int			$quality		JPEG Quality in percent
	 *	@return		bool
	 */
	public static function rotateImage( string $imageUri, int $angle, int $quality = 100 ): bool
	{
		$modifier	= new Rotator( $imageUri, $imageUri, $quality );
		return $modifier->rotate( $angle );
	}
}
