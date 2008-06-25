<?php
import( 'de.ceus-media.ui.image.Creator' );
import( 'de.ceus-media.ui.image.Drawer' );
import( 'de.ceus-media.ui.image.Printer' );
/**
 *	Creates and displays Error Image with Message.
 *	@package		ui.image
 *	@uses			UI_Image_Creator
 *	@uses			UI_Image_Drawer
 *	@uses			UI_Image_Printer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
/**
 *	Creates and displays Error Image with Message.
 *	@package		ui.image
 *	@uses			UI_Image_Creator
 *	@uses			UI_Image_Drawer
 *	@uses			UI_Image_Printer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2008
 *	@version		0.1
 */
class UI_Image_Error
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
	public function __construct( $message, $width = 200, $height = 20, $posX = 5, $posY = 3 )
	{
		$image	= new UI_Image_Creator();
		$image->create( $width, $height );
		$image	= new UI_Image_Drawer( $image->getResource() );
		$color	= $image->getColor( 255, 0, 0 );
		$image->drawBorder( $color, self::$borderWidth );
		$image->drawString( $posX, $posY, $message, 3, $color ); 
		UI_Image_Printer::showImage( $image->getImage(), 0, 100, self::$sendHeader );
	}
}
?>