<?php
namespace CeusMedia\Common\CLI;

class Dimensions
{
	protected static $colors	= 0;
	protected static $width		= 0;
	protected static $height	= 0;

	/**
	 *	...
	 *	@static		public
	 *	@access		public
	 *	@return		...
	 */
	public static function getCols( bool $force = FALSE ): int
	{
		return self::getWidth( $force );
	}

	/**
	 *	...
	 *	@static		public
	 *	@access		public
	 *	@return		...
	 */
	public static function getColors( bool $force = FALSE ): int
	{
		if( !self::$colors || $force )
			self::$colors	= intval( `tput colors` );
		return self::$colors;

	}

	/**
	 *	...
	 *	@static		public
	 *	@access		public
	 *	@return		...
	 */
	public static function getHeight( bool $force = FALSE ): int
	{
		if( !self::$height || $force )
			self::$height	= intval( `tput lines` );
//			self::$height	= exec( 'tput lines' );
		return self::$height;
	}

	/**
	 *	...
	 *	@static		public
	 *	@access		public
	 *	@return		object		Map of colors, height and width
	 */
	public static function getSize( bool $force = FALSE ): object
	{
/*		preg_match_all("/rows.([0-9]+);.columns.([0-9]+);/", strtolower(exec('stty -a |grep columns')), $output);
		if(sizeof($output) == 3) {
			self::$width	= $output[2][0];
			self::$height	= $output[1][0];
		}*/
		return (object) array(
			'colors'	=> self::getColors(),
			'height'	=> self::getHeight(),
			'width'		=> self::getWidth(),
		);
	}

	/**
	 *	...
	 *	@static		public
	 *	@access		public
	 *	@return		...
	 */
	public static function getWidth( bool $force = FALSE ): int
	{
		if( !self::$width || $force )
			self::$width	= intval( `tput cols` );
//			self::$width	= exec( 'tput cols' );
		return self::$width;
	}
}
class Size extends Dimensions{}
