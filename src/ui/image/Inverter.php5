<?php
/**
 *	Inverting Images.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		ui.image
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.12.2005
 *	@version		0.1
 */
/**
 *	Inverting Images.
 *	@package		ui.image
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.12.2005
 *	@version		0.1
 */
class UI_Image_Inverter
{
	/*	@var		int			$quality		Quality of Target Image */
	protected $quality;
	/*	@var		array		$size			Sizes of Source Image */
	protected $size	= array();
	/*	@var		string		$source			Source File Name of Source Image */
	protected $source;
	/*	@var		string		$target			Target File Name of Target Image */
	protected $target;
	
	/**
	 *	Constructur.
	 *	@access		public
	 *	@param		string		$source 		File Name of Source Image
	 *	@param		string		$target 		File Name of Target Image
	 *	@param		int			$quality 		Quality of Target Image
	 *	@return		void
	 */
	public function __construct( $source, $target, $quality = 100 )
	{
		$this->setSource( $source );	
		$this->setTarget( $target );	
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
	 *	@param		string		$source 		File Name of Source Image
	 *	@return		void
	 */
	public function setSource( $source )
	{
		if( !file_exists( $source ) )
			throw new InvalidArgumentException( 'Image File "'.$source.'" is not existing.' );
		$size = @getimagesize( $source );
		if( !$size )
			throw new Exception( 'Source File "'.$source.'" is not a supported Image.' );
		$this->size		= $size;
		$this->source	= $source;
	}

	/**
	 *	Sets the File Name of Target Image.
	 *	@access		public
	 *	@param		string		$target 		File Name of resulting Target Image
	 *	@return		void
	 */
	public function setTarget( $target )
	{
		$this->target	= $target;
	}
	
	/**
	 *	Invertes Source Image.
	 *	@access		public
	 *	@return		bool
	 */
	public function invert()
	{
		if( !count( $this->size ) )
			throw new RuntimeException( 'No Source Image set.' );

		$target	= imagecreatetruecolor( $this->size[0], $this->size[1] );
		if( function_exists( 'imageantialias' ) )
			imageantialias( $target, TRUE );
		switch( $this->size[2] )
		{
			case 1:      //GIF
				$source	= imagecreatefromgif( $this->source );
				$this->invertImage( $source, $target );
				imagegif( $target, $this->target );
				break;
			case 2:      //JPEG
				$source	= imagecreatefromjpeg( $this->source );
				$this->invertImage( $source, $target );
				imagejpeg( $target, $this->target, $this->quality );
				break;
			case 3:      //PNG
				$source	= imagecreatefrompng( $this->source );
				$this->invertImage( $source, $target );
				imagepng( $target, $this->target );
				break;
			default:
				throw new Exception( 'Image Type is no supported.' );
		}
		return true;
	}
	
	/**
	 *	Invertes all Pixels of Source Image.
	 *	@access		protected
	 *	@param		int			$source 		File Name of resulting Source Image
	 *	@param		int			$target			File Name of resulting Target Image
	 *	@return		bool
	 */
	protected function invertImage( $source, &$target )
	{
		for( $x=0; $x<$this->size[0]; $x++ )
		{
			for( $y=0; $y<$this->size[1]; $y++ )
			{
				$color	= imagecolorat( $source, $x, $y );
				$color	= imagecolorsforindex( $source, $color );
				$color['red']	= 255 - $color['red'];
				$color['green']	= 255 - $color['green'];
				$color['blue']	= 255 - $color['blue'];
				$color	= imagecolorallocate( $target, $color['red'], $color['green'], $color['blue'] );
				imagesetpixel( $target, $x, $y, $color );
			}
		}
	}
}
?>