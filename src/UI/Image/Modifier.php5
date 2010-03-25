<?php
/**
 *	Abstract basic class for all image modifying classes.
 *
 *	Copyright (c) 2009-2010 Christian Würker (ceus-media.de)
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
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.2009
 *	@version		$Id$
 */
/**
 *	Abstract basic class for all image modifying classes.
 *	@category		cmClasses
 *	@package		ui.image
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.2009
 *	@version		$Id$
 */
abstract class UI_Image_Modifier
{
	/*	@var		int			$quality		Quality of Target Image */
	protected $quality;
	/*	@var		array		$size			Sizes of Source Image */
	protected $sourceInfo		= array();
	/*	@var		string		$source			Source image */
	protected $source;
	/*	@var		string		$sourceUri		Source image URI */
	protected $sourceUri;
	/*	@var		string		$target			Target image */
	protected $target;
	/*	@var		string		$targetUri		Target image URI */
	protected $targetUri;

	/**
	 *	Constructur.
	 *	@access		public
	 *	@param		string		$sourceUri 		File URI of Source Image
	 *	@param		string		$targetUri 		File URI of Target Image
	 *	@param		int			$quality 		Quality of Target Image
	 *	@return		void
	 */
	public function __construct( $sourceUri = NULL, $targetUri = NULL, $quality = 100 )
	{
		if( !is_null( $sourceUri ) )
			$this->setSourceUri( $sourceUri );
		if( !is_null( $targetUri ) )
			$this->setTargetUri( $targetUri );	
		$this->setQuality( $quality );	
	}

	/**
	 *	Sets the Quality of resulting Image.
	 *	@access		public
	 *	@param		int			$quality 		Quality of resulting Image
	 *	@return		void
	 */
	public function setQuality( $quality )
	{
		$this->quality	= $quality;
	}

	/**
	 *	Sets the File Name of Source Image.
	 *	@access		public
	 *	@param		string		$sourceUri 		File URI of Source Image
	 *	@return		void
	 */
	public function setSourceUri( $sourceUri )
	{
		if( !file_exists( $sourceUri ) )
			throw new InvalidArgumentException( 'Image source "'.$sourceUri.'" is not existing' );
		$info = @getimagesize( $sourceUri );
		if( !$info )
			throw new Exception( 'Image source "'.$sourceUri.'" is not of a supported type' );
		$this->sourceUri	= $sourceUri;
		$this->sourceInfo	= $info;
		$this->loadImage();
	}

	/**
	 *	Sets the File Name of Target Image.
	 *	@access		public
	 *	@param		string		$targetUri 		File URI of resulting Target Image
	 *	@return		void
	 */
	public function setTargetUri( $targetUri )
	{
		$this->targetUri	= $targetUri;
	}

	public function loadImage()
	{
		if( !$this->sourceUri )
			throw new RuntimeException( 'No source image URI set' );
		switch( $this->sourceInfo[2] )
		{
			case IMAGETYPE_GIF:
				$this->source	= imagecreatefromgif( $this->sourceUri );
				break;
			case IMAGETYPE_JPEG:
				$this->source	= imagecreatefromjpeg( $this->sourceUri );
				break;
			case IMAGETYPE_PNG:
				$this->source	= imagecreatefrompng( $this->sourceUri );
				break;
			default:
				throw new Exception( 'Image type "'.$this->sourceInfo['mime'].'" is no supported' );
		}
	}

	/**
	 *	Saves target image source to image file.
	 *	@access		public
	 *	@param		int			$type			Output format type
	 *	@return		bool
	 */
	public function saveImage( $type = NULL )
	{
		if( !$this->source )
			throw new RuntimeException( 'No image loaded' );
		if( !$this->target )
			throw new RuntimeException( 'No modification applied' );
		if( !$this->targetUri )
			throw new RuntimeException( 'No target image URI set' );
		$type	= $type ? $type : $this->sourceInfo[2];
		switch( $type )
		{
			case IMAGETYPE_GIF:
				return imagegif( $this->target, $this->targetUri );
			case IMAGETYPE_JPEG:
				return imagejpeg( $this->target, $this->targetUri, $this->quality );
			case IMAGETYPE_PNG:
				return imagepng( $this->target, $this->targetUri );
			default:
				throw new Exception( 'Image Type "'.$type.'" is no supported' );
		}
	}
}
?>