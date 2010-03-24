<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		ui.image
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			Code Doc
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		ui.image
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			Code Doc
 */
class UI_Image_Creator
{
	const TYPE_PNG	= 0;
	const TYPE_JPEG	= 1;
	const TYPE_GIF	= 2;

	protected $height		= -1;
	protected $resource		= NULL;
	protected $type			= NULL;
	protected $width		= -1;

	public function create( $width, $height )
	{
		$this->resource	= imagecreatetruecolor( $width, $height );
		$this->width	= $width;
		$this->height	= $height;
	}
	
	public function getExtension()
	{
		return $this->extension;	
	}
	
	public function getHeight()
	{
		return $this->height;
	}
	
	public function getResource()
	{
		return $this->resource;
	}
	
	public function getType()
	{
		return $this->type;	
	}
	
	public function getWidth()
	{
		return $this->width;
	}
	
	public function loadImage( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new InvalidArgumentException( 'Image File "'.$fileName.'" is not existing.' );
		$info		= pathinfo( $fileName );
		$extension	= strtolower( $info['extension'] );
		switch( $extension )
		{
			case 'png':
				$this->resource	= imagecreatefrompng( $fileName );
				$this->type	= self::TYPE_PNG;
				break;
			case 'jpe':
			case 'jpeg':
			case 'jpg':
				$this->resource	= imagecreatefromjpeg( $fileName );
				$this->type	= self::TYPE_JPEG;
				break;
			case 'gif':
				$this->resource	= imagecreatefromgif( $fileName );
				$this->type	= self::TYPE_GIF;
				break;
			default:
				throw new InvalidArgumentException( 'Image Type "'.$extension.'" is not supported.' );
		}
		$this->extension	= $extension;
		$this->width		= imagesx( $this->resource );		
		$this->height		= imagesy( $this->resource );		
	}
}
?>