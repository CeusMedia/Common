<?php
/**
 *	Image resource reader and writer.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@package		UI
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Image resource reader and writer.
 *	@category		cmClasses
 *	@package		UI
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			Code Doc
 */
class UI_Image
{
	protected $width	= 0;
	protected $height	= 0;
	protected $quality	= 100;
	protected $resource	= NULL;
	protected $fileName	= NULL;
	protected $type		= IMAGETYPE_PNG;

	public function __construct(){}

	public function  __destruct()
	{
		if( $this->resource )
			imagedestroy( $this->resource );
	}

	public function create( $width, $height, $alpha = 0 )
	{
		if( $this->resource )
			imagedestroy( $this->resource );
		$this->resource	= imagecreatetruecolor( $width, $height );
		$this->width	= $width;
		$this->height	= $height;
	}

	/**
	 *	...
	 *	@access		public
	 *	@return		string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}

	/**
	 *	Returns height.
	 *	@access		public
	 *	@return		integer
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 *	Returns MIME type of image.
	 *	@access		public
	 *	@return		string
	 */
	public function getMimeType()
	{
		return image_type_to_mime_type( $this->type );
	}

	/**
	 *	Returns quality.
	 *	@access		public
	 *	@return		integer
	 */
	public function getQuality()
	{
		return $this->quality;
	}

	/**
	 *	Returns inner image resource.
	 *	@access		public
	 *	@return		resource
	 */
	public function getResource()
	{
		return $this->resource;
	}

	/**
	 *	Returns image type.
	 *	@access		public
	 *	@return		integer
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 *	Returns width.
	 *	@access		public
	 *	@return		integer
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 *	Reads an image from file, supporting several file types.
	 *	@access		public
	 *	@param		string		$fileName		Name of image file
	 *	@return		void
	 *	@throws		RuntimeException if file is not existing
	 *	@throws		RuntimeException if file is not readable
	 *	@throws		RuntimeException if file is not an image
	 *	@throws		Exception if image type is not supported for reading
	 */
	public function load( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new RuntimeException( 'Image "'.$fileName.'" is not existing' );
		if( !is_readable( $fileName ) )
			throw new RuntimeException( 'Image "'.$fileName.'" is not readable' );
		$info = getimagesize( $fileName );
		if( !$info )
			throw new Exception( 'Image "'.$fileName.'" is not of a supported type' );
		if( $this->resource )
			imagedestroy( $this->resource );
		switch( $info[2] )
		{
			case IMAGETYPE_GIF:
				$this->resource	= imagecreatefromgif( $fileName );
				break;
			case IMAGETYPE_JPEG:
				$this->resource	= imagecreatefromjpeg( $fileName );
				break;
			case IMAGETYPE_PNG:
				$this->resource	= imagecreatefrompng( $fileName );
				break;
			default:
				throw new Exception( 'Image type "'.$info['mime'].'" is no supported, detected '.$info[2] );
		}
		$this->fileName	= $fileName;
		$this->width	= $info[0];
		$this->height	= $info[1];
		$this->type		= $info[2];
		$this->mimeType	= $info['mime'];
	}

	/**
	 *	Writes an image to file.
	 *	@access		public
	 *	@param		string		$fileName		Name of new image file
	 *	@param		integer		$type			Type of image (IMAGETYPE_GIF|IMAGETYPE_JPEG|IMAGETYPE_PNG)
	 *	@return		boolean
	 *	@throws		RuntimeException if neither file has been loaded before nor a file name is given
	 *	@throws		Exception if image type is not supported for writing
	 */
	public function save( $fileName = NULL, $type = NULL )
	{
		if( !$type )
			$type	= $this->type;
		if( !$fileName )
			$fileName	= $this->fileName;
		if( !$fileName )
			throw new RuntimeException( 'No image file name set' );
		switch( $type )
		{
			case IMAGETYPE_GIF:
				return imagegif( $this->resource, $fileName );
			case IMAGETYPE_JPEG:
				return imagejpeg( $this->resource, $fileName, $this->quality );
			case IMAGETYPE_PNG:
				return imagepng( $this->resource, $fileName );
			default:
				throw new Exception( 'Image type "'.$type.'" is no supported' );
		}
	}
}
?>
