<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	...
 *
 *	Copyright (c) 2010-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\Image;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\UI\HTML\Tag as HtmlTag;
use CeusMedia\Common\UI\Image;
use Exception;
use RuntimeException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Exif extends Dictionary
{
	protected string $imageUri;

    protected array $raw;

	public function __construct( string $imageUri )
	{
		if( !function_exists( 'exif_read_data' ) )
			throw new RuntimeException( 'Exif not supported' );
		if( !file_exists( $imageUri ) )
			throw new RuntimeException( 'Image file "'.$imageUri.'" is not existing' );

		$this->imageUri	= $imageUri;
		$this->raw		= exif_read_data( $imageUri );
		foreach( $this->raw as $key => $value )
		{
			if( $key == "MakerNote" )
				continue;
			if( preg_match( "/^UndefinedTag/i", $key ) )
				continue;
			if( is_array( $value ) )
				foreach( $value as $nestKey => $nestValue )
					$this->set( $key.".".$nestKey, $nestValue );
			else
				$this->set( $key, $value );
		}
	}

	public function getRawData(): array
	{
		return $this->raw;
	}

	public function getThumbnailData(): array
	{
		$content	= exif_thumbnail( $this->imageUri, $width, $height, $type );
		return [
			'content'	=> $content,
			'width'		=> $width,
			'height'	=> $height,
			'type'		=> $type
		];
	}

	public function getThumbnailImage(): string
	{
		$content	= exif_thumbnail( $this->imageUri, $width, $height, $type );
		if( !$content )
			throw new Exception( 'No thumbnail available' );
		$attributes	= [
			'width'		=> $width,
			'height'	=> $height,
			'src'		=> 'data:image/gif;base64,'.base64_encode( $content )
		];
		return HtmlTag::create( 'img', NULL, $attributes );
	}
}
