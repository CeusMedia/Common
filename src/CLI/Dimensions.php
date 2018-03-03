<?php
class CLI_Dimensions{

	static protected $colors	= 0;
	static protected $width		= 0;
	static protected $height	= 0;

	/**
	 *	...
	 *	@static		public
	 *	@access		public
	 *	@return		...
	 */
	static public function getCols( $force = FALSE ){
		return $this->getWidth( $force );
	}

	/**
	 *	...
	 *	@static		public
	 *	@access		public
	 *	@return		...
	 */
	static public function getColors( $force = FALSE ){
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
	static public function getHeight( $force = FALSE ){
		if( !self::$height || $force )
			self::$height	= intval( `tput lines` );
//			self::$height	= exec( 'tput lines' );
		return self::$height;
	}

	/**
	 *	...
	 *	@static		public
	 *	@access		public
	 *	@return		...
	 */
	static public function getSize( $force = FALSE ){
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
	static public function getWidth( $force = FALSE ){
		if( !self::$width || $force )
			self::$width	= intval( `tput cols` );
//			self::$width	= exec( 'tput cols' );
		return self::$width;
	}
}
class CLI_Size extends CLI_Dimensions{}
?>
