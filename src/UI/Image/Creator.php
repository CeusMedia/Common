<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	...
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */

namespace CeusMedia\Common\UI\Image;

use GdImage;
use InvalidArgumentException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class Creator
{
	protected int $height				= -1;
	protected GdImage|null $resource	= NULL;
	protected ?int $type				= NULL;
	protected int $width				= -1;
    protected ?string $extension		= NULL;

	public function create( int $width, int $height, int $backgroundRed = 255, int $backgroundGreen = 255, int $backgroundBlue = 255, int $alpha = 0 ): void
	{
		$this->resource	= imagecreatetruecolor( $width, $height );
		$this->width	= $width;
		$this->height	= $height;
		$backColor		= imagecolorallocatealpha( $this->resource, $backgroundRed, $backgroundGreen, $backgroundBlue, $alpha );
		imagefilledrectangle( $this->resource, 0, 0, $width - 1, $height - 1, $backColor );
	}

	public function getExtension(): ?string
	{
		return $this->extension;
	}

	public function getHeight(): int
	{
		return $this->height;
	}

	public function getResource(): ?GdImage
	{
		return $this->resource;
	}

	public function getType(): ?int
	{
		return $this->type;
	}

	public function getWidth(): int
	{
		return $this->width;
	}

	public function loadImage( string $fileName ): void
	{
		if( !file_exists( $fileName ) )
			throw new InvalidArgumentException( 'Image File "'.$fileName.'" is not existing.' );
		$info		= pathinfo( $fileName );
		$extension	= strtolower( $info['extension'] );
		switch( $extension ){
			case 'png':
				$this->resource	= imagecreatefrompng( $fileName );
				$this->type	= IMAGETYPE_PNG;
				break;
			case 'jpe':
			case 'jpeg':
			case 'jpg':
				$this->resource	= imagecreatefromjpeg( $fileName );
				$this->type	= IMAGETYPE_JPEG;
				break;
			case 'gif':
				$this->resource	= imagecreatefromgif( $fileName );
				$this->type	= IMAGETYPE_GIF;
				break;
			default:
				throw new InvalidArgumentException( 'Image Type "'.$extension.'" is not supported.' );
		}
		$this->extension	= $extension;
		$this->width		= imagesx( $this->resource );
		$this->height		= imagesy( $this->resource );
	}
}
