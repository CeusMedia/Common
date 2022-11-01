<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Creates and displays Error Image with Message.
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
 */

namespace CeusMedia\Common\UI\Image;

/**
 *	Creates and displays Error Image with Message.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Error
{
	/**	 @var		int			$borderWidth	Width of Border around Image */
	static public $borderWidth	= 0;

	/**	 @var		bool		$sendHeader		Send Header with Image MIME Type */
	static public $sendHeader	= TRUE;

	/**
	 *	Constructor, display Error Image.
	 *	@access		public
	 *	@param		string		$message		Error Message
	 *	@param		int			$width			Image Width
	 *	@param		int			$height			Image Height
	 *	@param		int			$posX			X Position of Message
	 *	@param		int			$posY			Y Position of Message
	 *	@return		void
	 */
	public function __construct( string $message, int $width = 200, int $height = 20, int $posX = 5, int $posY = 3 )
	{
		$image	= new Creator();
		$image->create( $width, $height );
		$image	= new Drawer( $image->getResource() );
		$color	= $image->getColor( 255, 0, 0 );
		$image->drawBorder( $color, self::$borderWidth );
		$image->drawString( $posX, $posY, $message, 3, $color );
		Printer::showImage( $image->getImage(), IMAGETYPE_PNG, 100, self::$sendHeader );
	}
}
