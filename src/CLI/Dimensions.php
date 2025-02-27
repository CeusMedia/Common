<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Terminal dimensions.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI;

/**
 *	Terminal dimensions.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Dimensions
{
	protected static int $colors	= 0;

	protected static int $width		= 0;

	protected static int $height	= 0;

	/**
	 *	Returns number of available colors.
	 *	@static		public
	 *	@access		public
	 *	@param		bool		$force		Flag: ignore prior detected colors, default: no
	 *	@return		int
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
	 *	@param		bool		$force		Flag: ignore prior detected height, default: no
	 *	@return		int
	 */
	public static function getHeight( bool $force = FALSE ): int
	{
		if( !self::$height || $force )
			self::$height	= intval( `tput lines` );
		return self::$height;
	}

	/**
	 *	...
	 *	@static		public
	 *	@access		public
	 *	@param		bool		$force		Flag: ignore prior detected values, default: no
	 *	@return		object		Map of colors, height and width
	 */
	public static function getSize( bool $force = FALSE ): object
	{
/*		preg_match_all("/rows.([0-9]+);.columns.([0-9]+);/", strtolower(exec('stty -a |grep columns')), $output);
		if(sizeof($output) == 3) {
			self::$width	= $output[2][0];
			self::$height	= $output[1][0];
		}*/
		return (object) [
			'colors'	=> self::getColors( $force ),
			'height'	=> self::getHeight( $force ),
			'width'		=> self::getWidth( $force ),
		];
	}

	/**
	 *	...
	 *	@static		public
	 *	@access		public
	 *	@param		bool		$force		Flag: ignore prior detected width, default: no
	 *	@return		int
	 */
	public static function getWidth( bool $force = FALSE ): int
	{
		if( !self::$width || $force )
			self::$width	= intval( `tput cols` );
		return self::$width;
	}
}
