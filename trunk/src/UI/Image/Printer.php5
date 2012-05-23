<?php
/**
 *	Prints an Image Resource into a File or on Screen.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.06.2008
 *	@version		$Id$
 */
/**
 *	Prints an Image Resource into a File or on Screen.
 *	@category		cmClasses
 *	@package		UI.Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.06.2008
 *	@version		$Id$
 */
class UI_Image_Printer
{
	/**	@var		resource		$resource		Image Resource */
	protected $resource;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		resource		$resource		Image Resource
	 *	@return		void
	 */
	public function __construct( $resource )
	{
		if( !is_resource( $resource ) )
			throw new InvalidArgumentException( 'Given Image Resource is not a valid Resource.' );
		$this->resource	= $resource;
	}
	
	/**
	 *	Print Image on Screen.
	 *	@access		public
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@param		bool		$showHeader		Flag: set Image MIME Type Header
	 *	@return		void
	 */
	public function show( $type = IMAGETYPE_PNG, $quality = 100, $sendHeader = TRUE )
	{
		$this->showImage( $this->resource, $type, $quality, $sendHeader );
	}
	
	/**
	 *	Writes Image to File.
	 *	@access		public
	 *	@param		string		$fleName		Name of target Image File
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@return		void
	 */
	public function save( $fileName, $type = IMAGETYPE_PNG, $quality = 100 )
	{
		$this->saveImage( $fileName, $this->resource, $type, $quality );
	}
	
	/**
	 *	Prints an Image to Screen statically.
	 *	@access		public
	 *	@static
	 *	@param		resource	$resource		Image Resource
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@param		bool		$showHeader		Flag: set Image MIME Type Header
	 *	@return		void
	 */
	public static function showImage( $resource, $type = IMAGETYPE_PNG, $quality = 100, $sendHeader = TRUE )
	{
		switch( $type )
		{
			case IMAGETYPE_GIF:
				if( $sendHeader )
					header( "Content-type: image/gif" );
				ImageGIF( $resource );
				break;
			case IMAGETYPE_JPEG:
				if( $sendHeader )
					header( "Content-type: image/jpeg" );
				ImageJPEG( $resource, "", $quality );
				break;
			case IMAGETYPE_PNG:
				if( $sendHeader )
					header( "Content-type: image/png" );
				ImagePNG( $resource );
				break;
			default:
				throw new InvalidArgumentException( 'Invalid Image Type' );
		}
	}
	
	/**
	 *	Saves an Image to File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fleName		Name of target Image File
	 *	@param		resource	$resource		Image Resource
	 *	@param		int			$type			Image Type
	 *	@param		int			$quality		JPEG Quality (1-100)
	 *	@return		void
	 */
	public static function saveImage( $fileName, $resource, $type = IMAGETYPE_PNG, $quality = 100 )
	{
		switch( $type )
		{
			case IMAGETYPE_PNG:
				ImagePNG( $resource, $fileName );
				break;
			case IMAGETYPE_JPEG:
				ImageJPEG( $resource, $fileName, $quality );
				break;
			case IMAGETYPE_GIF:
				ImageGIF( $resource, $fileName );
				break;
			default:
				throw new InvalidArgumentException( 'Invalid Image Type' );
		}
	}
}
?>