<?php
/**
 *	...
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		UI.Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		UI.Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class UI_Image_Exif extends ADT_List_Dictionary
{
	protected $imageUri;

	public function __construct( $imageUri )
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

	public function getThumbnailData()
	{
		$content	= exif_thumbnail( $this->imageUri, $width, $height, $type );
		return array(
			'content'	=> $content,
			'width'		=> $width,
			'height'	=> $height,
			'type'		=> $type
		);
	}

	public function getThumbnailImage()
	{
		$content	= exif_thumbnail( $this->imageUri, $width, $height, $type );
		if( !$content )
			throw new Exception( 'No thumbnail available' );
		$attributes	= array(
			'width'		=> $width,
			'height'	=> $height,
			'src'		=> 'data:image/gif;base64,'.base64_encode( $content )
		);
		return UI_HTML_Tag::create( 'img', NULL, $attributes );
	}
	
	public function getRawData()
	{
		return $this->raw;
	}
}
?>